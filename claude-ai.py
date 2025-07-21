import os
import anthropic
import json
import re
from datetime import datetime
from dotenv import load_dotenv
from pathlib import Path

load_dotenv()

def validate_api_key():
    """Validate that the API key is properly configured"""
    api_key = os.getenv("ANTHROPIC_API_KEY")
    
    if not api_key:
        print("Error: ANTHROPIC_API_KEY not found in environment variables")
        print("Please check your .env file or set the environment variable")
        return False
    
    if not api_key.startswith("sk-ant-"):
        print("Error: Invalid API key format. Anthropic API keys should start with 'sk-ant-'")
        return False
    
    print(f"API Key found: {api_key[:12]}...{api_key[-4:]} (masked)")
    return True

def get_model_configs():
    """Return available model configurations with increased token limits"""
    return {
        "sonnet": {
            "name": "claude-sonnet-4-20250514",
            "params": {
                "max_tokens": 8192  # Maximum for Sonnet
            },
            "use_streaming": False,
            "display_name": "Claude Sonnet 4 (Fast, Efficient)",
            "chunk_size": 8000,  # Characters per chunk
            "files_per_chunk": 8
        },
        "opus": {
            "name": "claude-opus-4-20250514",
            "params": {
                "max_tokens": 30598,  # Maximum for Opus
                "temperature": 1,
                "thinking": {
                    "type": "enabled",
                    "budget_tokens": 24478
                }
            },
            "use_streaming": True,
            "display_name": "Claude Opus 4 (Advanced, with Reasoning)",
            "chunk_size": 25000,  # Larger chunks for Opus
            "files_per_chunk": 15
        }
    }

def choose_model():
    """Let user choose between Sonnet and Opus"""
    models = get_model_configs()
    
    print("\nChoose your model:")
    print("1. Sonnet 4 - Fast and efficient for most tasks (8K tokens)")
    print("2. Opus 4 - Advanced reasoning, larger context (30K tokens)")
    print("3. Exit")
    
    while True:
        choice = input("Enter your choice (1-3): ").strip()
        
        if choice == "1":
            return models["sonnet"]
        elif choice == "2":
            return models["opus"]
        elif choice == "3":
            return None
        else:
            print("Invalid choice. Please enter 1, 2, or 3.")

def test_model(model_config):
    """Test a specific model to see if it works"""
    client = anthropic.Anthropic(api_key=os.getenv("ANTHROPIC_API_KEY"))
    
    model_name = model_config["name"]
    model_params = model_config["params"]
    use_streaming = model_config.get("use_streaming", False)
    
    test_message = "Hello, this is a test message. Please respond with just 'Test successful'."
    
    print(f"Testing {model_config['display_name']}...")
    try:
        if use_streaming:
            response_text = ""
            with client.messages.stream(
                model=model_name,
                messages=[{"role": "user", "content": test_message}],
                **model_params
            ) as stream:
                for text in stream.text_stream:
                    response_text += text
            
            print(f"✅ SUCCESS: {model_config['display_name']}")
            return True
        else:
            response = client.messages.create(
                model=model_name,
                messages=[{"role": "user", "content": test_message}],
                **model_params
            )
            print(f"✅ SUCCESS: {model_config['display_name']}")
            return True
        
    except anthropic.AuthenticationError as e:
        print(f"❌ Authentication Error: {e}")
    except anthropic.PermissionDeniedError as e:
        print(f"❌ Permission Denied: {e}")
    except anthropic.RateLimitError as e:
        print(f"❌ Rate Limit Error: {e}")
    except anthropic.APIError as e:
        print(f"❌ API Error: {e}")
    except Exception as e:
        print(f"❌ Unexpected error: {e}")
    
    return False

def should_include_file(file_path):
    """Enhanced file filtering with size and pattern checks"""
    exclude_patterns = [
        'Test.php', 'vendor/', '.git/', 'node_modules/', 
        'storage/', 'bootstrap/cache/', '.env', 'composer.lock',
        'package-lock.json', '.phpunit', 'coverage/', 'prompt.txt', 'output.txt'
    ]
    
    try:
        # Increased file size limit for more comprehensive analysis
        if os.path.getsize(file_path) > 50000:  # Increased from 10KB to 50KB
            return False
    except OSError:
        return False
        
    return not any(pattern in file_path for pattern in exclude_patterns)

def load_laravel_project(mode='summary'):
    """Load Laravel project with different modes to manage token count"""
    context = ""
    
    folders = [
        'app/Http/Controllers',
        'app/Models', 
        'resources/views/',
        'public/assets/js',
        'public/assets/css',
        'routes/',
        'database/migrations',
        'config/'
    ]
    
    if mode == 'summary':
        context += "=== PROJECT STRUCTURE SUMMARY ===\n"
        for folder in folders:
            if os.path.exists(folder):
                context += f"\n{folder}/:\n"
                for root, dirs, files in os.walk(folder):
                    for file in files:
                        if file.endswith(('.php', '.blade.php', '.js', '.css')):
                            file_path = os.path.join(root, file)
                            if should_include_file(file_path):
                                try:
                                    size = os.path.getsize(file_path)
                                    context += f"  - {file_path} ({size} bytes)\n"
                                except OSError:
                                    continue
    
    elif mode == 'limited':
        for folder in folders:
            if os.path.exists(folder):
                context += f"\n=== {folder.upper()} ===\n"
                for root, dirs, files in os.walk(folder):
                    for file in files[:5]:  # Increased from 3 to 5 files
                        if file.endswith(('.php', '.blade.php', '.js', '.css')):
                            file_path = os.path.join(root, file)
                            
                            if not should_include_file(file_path):
                                continue
                                
                            try:
                                with open(file_path, 'r', encoding='utf-8') as f:
                                    content = f.read()
                                    # Increased content limit
                                    if len(content) > 8000:  # Increased from 3000 to 8000
                                        content = content[:8000] + "\n... [TRUNCATED] ..."
                                    
                                    context += f"\n--- {file_path} ---\n"
                                    context += content
                                    context += "\n"
                            except (UnicodeDecodeError, IOError) as e:
                                print(f"Warning: Could not read {file_path}: {e}")
                                continue
    
    return context

def chunk_project_by_folder(model_config):
    """Process the project folder by folder with dynamic chunk sizing"""
    folders = [
        'app/Http/Controllers',
        'app/Models', 
        'resources/views/admin',
        'public/assets/js',
        'public/assets/css',
        'routes/',
        'database/migrations'
    ]
    
    chunks = []
    chunk_size = model_config.get('chunk_size', 8000)
    files_per_chunk = model_config.get('files_per_chunk', 8)
    
    for folder in folders:
        if os.path.exists(folder):
            chunk_context = f"\n=== {folder.upper()} ===\n"
            file_count = 0
            
            for root, dirs, files in os.walk(folder):
                for file in files:
                    if file.endswith(('.php', '.blade.php', '.js', '.css')) and file_count < files_per_chunk:
                        file_path = os.path.join(root, file)
                        
                        if not should_include_file(file_path):
                            continue
                            
                        try:
                            with open(file_path, 'r', encoding='utf-8') as f:
                                content = f.read()
                                # Dynamic content limiting based on model capacity
                                if len(content) > chunk_size:
                                    content = content[:chunk_size] + "\n... [TRUNCATED] ..."
                                
                                chunk_context += f"\n--- {file_path} ---\n"
                                chunk_context += content
                                chunk_context += "\n"
                                file_count += 1
                        except (UnicodeDecodeError, IOError) as e:
                            print(f"Warning: Could not read {file_path}: {e}")
                            continue
            
            if chunk_context.strip():
                chunks.append({
                    'folder': folder,
                    'content': chunk_context
                })
    
    return chunks

def read_prompt():
    try:
        with open('prompt.txt', 'r', encoding='utf-8') as f:
            return f.read().strip()
    except FileNotFoundError:
        print("Error: prompt.txt file not found!")
        return None
    except Exception as e:
        print(f"Error reading prompt.txt: {e}")
        return None

def create_backup():
    """Create a backup of the project before making changes"""
    timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
    backup_dir = f"backup_{timestamp}"
    
    try:
        os.makedirs(backup_dir, exist_ok=True)
        
        folders_to_backup = [
            'app/Http/Controllers',
            'app/Models',
            'resources/views/admin',
            'routes/',
        ]
        
        for folder in folders_to_backup:
            if os.path.exists(folder):
                import shutil
                backup_folder = os.path.join(backup_dir, folder)
                os.makedirs(os.path.dirname(backup_folder), exist_ok=True)
                shutil.copytree(folder, backup_folder, dirs_exist_ok=True)
        
        print(f"✅ Backup created: {backup_dir}")
        return backup_dir
    except Exception as e:
        print(f"❌ Backup failed: {e}")
        return None

def parse_file_changes(response_text):
    """Enhanced file change parsing with more aggressive pattern matching"""
    changes = []
    
    # Enhanced delete patterns
    delete_patterns = [
        r'(?:DELETE FILE|REMOVE FILE|Remove:|Delete:|Remove file:|Delete file:)\s*([^\n]+)',
        r'(?:rm|delete)\s+([^\n]+\.(?:php|blade\.php|js|css))',
        r'(?:DELETE DIRECTORY|REMOVE DIRECTORY|Remove dir:|Delete dir:|Remove directory:|Delete directory:)\s*([^\n]+)',
        r'(?:rmdir|rm -rf)\s+([^\n]+)',
        r'❌\s*([^\n]+\.(?:php|blade\.php|js|css))',  # Emoji-based deletion
        r'🗑️\s*([^\n]+)',  # Trash emoji
    ]
    
    for pattern in delete_patterns:
        matches = re.finditer(pattern, response_text, re.MULTILINE | re.IGNORECASE)
        for match in matches:
            path = match.group(1).strip()
            if os.path.isdir(path):
                changes.append({
                    'path': path,
                    'action': 'delete_directory',
                    'type': 'directory'
                })
            else:
                changes.append({
                    'path': path,
                    'action': 'delete_file',
                    'type': 'file'
                })
    
    # Enhanced move patterns
    move_patterns = [
        r'(?:MOVE|RENAME|mv)\s+([^\s]+)\s+(?:to|->|→)\s+([^\n]+)',
        r'(?:Move|Rename)\s*([^\n]+)\s*(?:to|->|→)\s*([^\n]+)',
        r'📦\s*([^\s]+)\s*(?:to|->|→)\s*([^\n]+)',  # Box emoji for moves
        r'🔄\s*([^\s]+)\s*(?:to|->|→)\s*([^\n]+)'   # Refresh emoji for moves
    ]
    
    for pattern in move_patterns:
        matches = re.finditer(pattern, response_text, re.MULTILINE | re.IGNORECASE)
        for match in matches:
            old_path = match.group(1).strip()
            new_path = match.group(2).strip()
            changes.append({
                'old_path': old_path,
                'new_path': new_path,
                'action': 'move',
                'type': 'move'
            })
    
    # Enhanced directory creation patterns
    mkdir_patterns = [
        r'(?:CREATE DIRECTORY|MAKE DIRECTORY|mkdir)\s+([^\n]+)',
        r'(?:Create dir:|Make dir:|Create directory:|Make directory:)\s*([^\n]+)',
        r'📁\s*(?:Create|Make)?\s*([^\n]+)'  # Folder emoji
    ]
    
    for pattern in mkdir_patterns:
        matches = re.finditer(pattern, response_text, re.MULTILINE | re.IGNORECASE)
        for match in matches:
            dir_path = match.group(1).strip()
            changes.append({
                'path': dir_path,
                'action': 'create_directory',
                'type': 'directory'
            })
    
    # Enhanced file creation/update patterns
    file_patterns = [
        r'(?:CREATE FILE|NEW FILE|File:|Filename:|Create file:|New file:)\s*([^\n]+\.(?:php|blade\.php|js|css|json))',
        r'```(?:php|blade|javascript|css|json)?\s*(?://\s*)?([^\n]+\.(?:php|blade\.php|js|css|json))\s*\n(.*?)```',
        r'--- ([^\n]+\.(?:php|blade\.php|js|css|json)) ---\n(.*?)(?=\n---|$)',
        r'📝\s*([^\n]+\.(?:php|blade\.php|js|css|json))',  # Pencil emoji
        r'✏️\s*([^\n]+\.(?:php|blade\.php|js|css|json))',  # Pencil emoji variant
        r'(?:UPDATE FILE|MODIFY FILE|Update file:|Modify file:)\s*([^\n]+\.(?:php|blade\.php|js|css|json))'
    ]
    
    for pattern in file_patterns:
        matches = re.finditer(pattern, response_text, re.MULTILINE | re.DOTALL)
        for match in matches:
            if len(match.groups()) == 2:
                file_path = match.group(1).strip()
                content = match.group(2).strip()
                changes.append({
                    'path': file_path,
                    'content': content,
                    'action': 'create_or_update',
                    'type': 'file'
                })
            elif len(match.groups()) == 1:
                file_path = match.group(1).strip()
                changes.append({
                    'path': file_path,
                    'action': 'mentioned',
                    'type': 'file'
                })
    
    return changes

def apply_file_changes(changes, force_apply=False):
    """Apply the parsed changes to actual files and directories with force option"""
    applied_changes = []
    errors = []
    
    action_priority = {
        'delete_file': 1,
        'delete_directory': 2,
        'move': 3,
        'create_directory': 4,
        'create_or_update': 5
    }
    
    changes = sorted(changes, key=lambda x: action_priority.get(x['action'], 999))
    
    for change in changes:
        action = change['action']
        
        try:
            if action == 'delete_file':
                file_path = change['path']
                if os.path.exists(file_path):
                    os.remove(file_path)
                    applied_changes.append(f"🗑️ Deleted file: {file_path}")
                    print(f"🗑️ Deleted file: {file_path}")
                else:
                    if force_apply:
                        print(f"⚠️ File not found (continuing): {file_path}")
                    else:
                        print(f"⚠️ File not found: {file_path}")
                    
            elif action == 'delete_directory':
                dir_path = change['path']
                if os.path.exists(dir_path):
                    import shutil
                    shutil.rmtree(dir_path)
                    applied_changes.append(f"🗑️ Deleted directory: {dir_path}")
                    print(f"🗑️ Deleted directory: {dir_path}")
                else:
                    if force_apply:
                        print(f"⚠️ Directory not found (continuing): {dir_path}")
                    else:
                        print(f"⚠️ Directory not found: {dir_path}")
                    
            elif action == 'move':
                old_path = change['old_path']
                new_path = change['new_path']
                if os.path.exists(old_path):
                    os.makedirs(os.path.dirname(new_path), exist_ok=True)
                    import shutil
                    shutil.move(old_path, new_path)
                    applied_changes.append(f"📦 Moved: {old_path} → {new_path}")
                    print(f"📦 Moved: {old_path} → {new_path}")
                else:
                    if force_apply:
                        print(f"⚠️ Source not found (continuing): {old_path}")
                    else:
                        print(f"⚠️ Source not found: {old_path}")
                    
            elif action == 'create_directory':
                dir_path = change['path']
                os.makedirs(dir_path, exist_ok=True)
                applied_changes.append(f"📁 Created directory: {dir_path}")
                print(f"📁 Created directory: {dir_path}")
                
            elif action == 'create_or_update':
                file_path = change['path']
                content = change.get('content', '')
                
                if not content and not force_apply:
                    print(f"⚠️ No content provided for: {file_path}")
                    continue
                
                # Force create directory structure
                os.makedirs(os.path.dirname(file_path), exist_ok=True)
                
                action_verb = "Updated" if os.path.exists(file_path) else "Created"
                
                with open(file_path, 'w', encoding='utf-8') as f:
                    f.write(content if content else "<?php\n// File created by analyzer\n")
                
                applied_changes.append(f"📝 {action_verb}: {file_path}")
                print(f"📝 {action_verb}: {file_path}")
                
        except Exception as e:
            error_msg = f"❌ Failed to {action} {change.get('path', change.get('old_path', 'unknown'))}: {e}"
            errors.append(error_msg)
            print(error_msg)
            
            if not force_apply:
                # Stop on first error if not forcing
                break
    
    return applied_changes, errors

def ask_claude_chunked(question, model_config):
    """Process project in chunks with enhanced token management"""
    try:
        client = anthropic.Anthropic(api_key=os.getenv("ANTHROPIC_API_KEY"))
        
        model_name = model_config["name"]
        model_params = model_config["params"]
        use_streaming = model_config.get("use_streaming", False)
        
        print(f"Using {model_config['display_name']}")
        print("Processing project in chunks to manage token limits...")
        
        project_summary = load_laravel_project(mode='summary')
        print(f"Project summary loaded ({len(project_summary)} characters)")
        
        chunks = chunk_project_by_folder(model_config)
        print(f"Project split into {len(chunks)} chunks")
        
        all_responses = []
        all_changes = []
        
        for i, chunk in enumerate(chunks):
            print(f"\n📁 Processing {chunk['folder']} ({i+1}/{len(chunks)})...")
            
            enhanced_prompt = f"""Laravel project analysis - Folder: {chunk['folder']}

PROJECT OVERVIEW:
{project_summary}

CURRENT FOLDER CONTENT:
{chunk['content']}

TASK: {question}

Focus specifically on issues in the {chunk['folder']} folder. 

CRITICAL INSTRUCTIONS:
1. Be AGGRESSIVE in identifying and fixing issues
2. ALWAYS provide complete file contents for any files you modify
3. Use these EXACT keywords for operations:

FILE OPERATIONS (use these exact phrases):
- CREATE FILE: path/to/file.php
- DELETE FILE: path/to/file.php  
- UPDATE FILE: path/to/file.php
- MOVE: old/path/file.php to new/path/file.php

DIRECTORY OPERATIONS (use these exact phrases):
- CREATE DIRECTORY: path/to/directory
- DELETE DIRECTORY: path/to/directory

CODE BLOCKS (always include complete file content):
```php
// app/Http/Controllers/ExampleController.php
<?php
[COMPLETE file content here - no shortcuts or placeholders]
```

REQUIREMENTS:
- Provide COMPLETE file contents for any files you create/update
- NO placeholders like "// existing code" or "// add more methods"
- Fix ALL identified issues in this folder
- Create missing files if needed
- Remove problematic files if necessary
- Suggest structural improvements

Be thorough and decisive in your recommendations."""
            
            try:
                if use_streaming:
                    response_text = ""
                    with client.messages.stream(
                        model=model_name,
                        messages=[{"role": "user", "content": enhanced_prompt}],
                        **model_params
                    ) as stream:
                        for text in stream.text_stream:
                            response_text += text
                            print(".", end="", flush=True)
                    print(f" ✅ {chunk['folder']} processed")
                else:
                    response = client.messages.create(
                        model=model_name,
                        messages=[{"role": "user", "content": enhanced_prompt}],
                        **model_params
                    )
                    response_text = response.content[0].text
                    print(f" ✅ {chunk['folder']} processed")
                
                all_responses.append(f"\n=== {chunk['folder']} ANALYSIS ===\n{response_text}")
                
                chunk_changes = parse_file_changes(response_text)
                all_changes.extend(chunk_changes)
                
            except Exception as e:
                print(f" ❌ Error processing {chunk['folder']}: {e}")
                continue
        
        combined_response = "\n".join(all_responses)
        
        timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        with open('output.txt', 'w', encoding='utf-8') as f:
            f.write(f"Generated: {timestamp}\n")
            f.write(f"Model: {model_config['display_name']}\n")
            f.write(f"Processing Mode: Chunked ({len(chunks)} chunks)\n")
            f.write("="*50 + "\n\n")
            f.write(combined_response)
        
        print(f"\n✅ All chunks processed. Response saved to output.txt")
        print(f"📊 Total changes found: {len(all_changes)}")
        
        return combined_response, all_changes
        
    except Exception as e:
        print(f"Error in chunked processing: {e}")
        return None, []

def ask_claude_summary_mode(question, model_config):
    """Ask Claude using summary mode for faster processing"""
    try:
        client = anthropic.Anthropic(api_key=os.getenv("ANTHROPIC_API_KEY"))
        
        project_context = load_laravel_project(mode='summary')
        
        model_name = model_config["name"]
        model_params = model_config["params"]
        use_streaming = model_config.get("use_streaming", False)
        
        print(f"Using summary mode with {model_config['display_name']}")
        
        enhanced_prompt = f"""Laravel project structure analysis:

{project_context}

Task: {question}

Since this is a summary view, please provide:
1. High-level analysis of potential issues based on file structure
2. Specific recommendations for each folder
3. Priority order for fixes
4. Common Laravel admin interface problems to look for
5. Suggested file operations (CREATE FILE, DELETE FILE, MOVE, etc.)

Focus on identifying patterns and structural issues that can be determined from the file organization.
Be AGGRESSIVE in your recommendations - if you see potential issues, suggest concrete fixes."""
        
        if use_streaming:
            print("Streaming response...")
            response_text = ""
            with client.messages.stream(
                model=model_name,
                messages=[{"role": "user", "content": enhanced_prompt}],
                **model_params
            ) as stream:
                for text in stream.text_stream:
                    response_text += text
                    print(".", end="", flush=True)
            print("\n✅ Summary analysis completed!")
        else:
            response = client.messages.create(
                model=model_name,
                messages=[{"role": "user", "content": enhanced_prompt}],
                **model_params
            )
            response_text = response.content[0].text
        
        timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        with open('output_summary.txt', 'w', encoding='utf-8') as f:
            f.write(f"Generated: {timestamp}\n")
            f.write(f"Model: {model_config['display_name']}\n")
            f.write("Processing Mode: Summary\n")
            f.write("="*50 + "\n\n")
            f.write(response_text)
        
        print("Summary analysis saved to output_summary.txt")
        return response_text
        
    except Exception as e:
        print(f"Error in summary mode: {e}")
        return None

if __name__ == "__main__":
    if not validate_api_key():
        exit(1)
    
    print("\n" + "="*50)
    print("ENHANCED LARAVEL PROJECT ANALYZER")
    print("="*50)
    
    # Let user choose model
    chosen_model = choose_model()
    if not chosen_model:
        print("Exiting...")
        exit(0)
    
    # Test the chosen model
    print(f"\n{'='*50}")
    print("TESTING CHOSEN MODEL")
    print("="*50)
    
    if not test_model(chosen_model):
        print(f"\n❌ {chosen_model['display_name']} is not available.")
        print("Please check:")
        print("1. Your API key permissions")
        print("2. Account billing status")
        print("3. Model availability in your region")
        exit(1)
    
    print(f"\n✅ Using {chosen_model['display_name']}")
    print("="*50)
    
    prompt = read_prompt()
    if prompt:
        print("\nChoose processing mode:")
        print("1. Summary mode (fast, high-level analysis)")
        print("2. Chunked mode (detailed, processes each folder separately)")
        print("3. Exit")
        
        choice = input("Enter your choice (1-3): ").strip()
        
        if choice == "1":
            print("Processing in summary mode...")
            ask_claude_summary_mode(prompt, chosen_model)
            
        elif choice == "2":
            print("Processing in chunked mode...")
            response, changes = ask_claude_chunked(prompt, chosen_model)
            
            if changes:
                print(f"\n🔍 Found {len(changes)} total changes across all folders")
                
                deletes = [c for c in changes if c['action'].startswith('delete')]
                moves = [c for c in changes if c['action'] == 'move']
                creates = [c for c in changes if c['action'] in ['create_directory', 'create_or_update']]
                
                if deletes:
                    print(f"\n🗑️ Files/Directories to DELETE ({len(deletes)}):")
                    for change in deletes[:5]:
                        print(f"  ❌ {change['path']}")
                    if len(deletes) > 5:
                        print(f"  ... and {len(deletes) - 5} more")
                
                if moves:
                    print(f"\n📦 Files/Directories to MOVE ({len(moves)}):")
                    for change in moves[:5]:
                        print(f"  🔄 {change['old_path']} → {change['new_path']}")
                    if len(moves) > 5:
                        print(f"  ... and {len(moves) - 5} more")
                
                if creates:
                    print(f"\n📝 Files/Directories to CREATE/UPDATE ({len(creates)}):")
                    for change in creates[:5]:
                        action_icon = "📁" if change['action'] == 'create_directory' else "📝"
                        print(f"  {action_icon} {change['path']}")
                    if len(creates) > 5:
                        print(f"  ... and {len(creates) - 5} more")
                
                print(f"\n⚠️ CAUTION: This will make {len(changes)} changes to your project!")
                print("Choose application mode:")
                print("1. Normal mode (stop on errors)")
                print("2. Force mode (continue despite errors)")
                print("3. Skip application")
                
                app_choice = input("Enter your choice (1-3): ").strip()
                
                if app_choice in ['1', '2']:
                    force_mode = app_choice == '2'
                    
                    if force_mode:
                        print("🚀 FORCE MODE: Will continue despite errors and create missing content")
                    else:
                        print("🔒 NORMAL MODE: Will stop on first error")
                    
                    backup_dir = create_backup()
                    if not backup_dir:
                        print("⚠️ Backup failed. Proceeding without backup...")
                    
                    applied, errors = apply_file_changes(changes, force_apply=force_mode)
                    
                    if applied:
                        print(f"\n✅ Successfully applied {len(applied)} changes")
                        if backup_dir:
                            print(f"🔄 Backup available at: {backup_dir}")
                        
                        # Show summary of what was done
                        print(f"\n📊 SUMMARY:")
                        print(f"  ✅ Applied: {len(applied)} changes")
                        print(f"  ❌ Errors: {len(errors)} errors")
                        
                        if force_mode and errors:
                            print(f"\n⚠️ Force mode errors (non-critical):")
                            for error in errors[:3]:
                                print(f"    {error}")
                            if len(errors) > 3:
                                print(f"    ... and {len(errors) - 3} more")
                    else:
                        print("\n❌ No changes were applied")
                    
                    if errors and not force_mode:
                        print(f"\n⚠️ {len(errors)} errors occurred - check output for details")
                        print("💡 Try force mode (option 2) to continue despite errors")
                        
                elif app_choice == '3':
                    print("Changes not applied. Review them in output.txt")
                else:
                    print("Invalid choice. Changes not applied.")
            else:
                print("No file changes detected in the responses")
                print("💡 Try using more specific prompts or check output.txt for analysis")
                
        elif choice == "3":
            print("Exiting...")
            exit(0)
            
        else:
            print("Invalid choice. Exiting...")
            exit(1)
    else:
        print("Please create a prompt.txt file with your question.")
        print("Example prompt: 'Analyze and fix all Laravel admin interface issues'")
        exit(1)