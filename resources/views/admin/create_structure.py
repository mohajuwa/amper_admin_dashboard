#!/usr/bin/env python3
"""
Script to create reusable Laravel Blade component structure
Place this file in resources/views/admin/ and run it
"""

import os
import json

def create_directory_structure():
    """Create the directory structure for reusable components"""
    
    # Base directory (current directory when running from resources/views/admin)
    base_dir = "."
    
    # Define the structure
    structure = {
        "partials": {
            "components": {
                "tables": {},
                "forms": {},
                "cards": {},
                "badges": {},
                "buttons": {},
                "layouts": {}
            },
            "form-fields": {},
            "widgets": {},
            "scripts": {}
        }
    }
    
    def create_dirs(path, structure_dict):
        """Recursively create directories"""
        for dir_name, sub_structure in structure_dict.items():
            dir_path = os.path.join(path, dir_name)
            if not os.path.exists(dir_path):
                os.makedirs(dir_path)
                print(f"✓ Created directory: {dir_path}")
            else:
                print(f"• Directory already exists: {dir_path}")
            
            if isinstance(sub_structure, dict) and sub_structure:
                create_dirs(dir_path, sub_structure)
    
    print("🚀 Creating Laravel Blade Component Structure...")
    print("=" * 50)
    
    create_dirs(base_dir, structure)
    
    # Create a manifest file to track the structure
    manifest = {
        "created_at": "2025",
        "description": "Reusable Laravel Blade Components Structure",
        "components": {
            "tables": [
                "generic-list-table",
                "responsive-data-table", 
                "mobile-cards-table",
                "table-header",
                "table-actions"
            ],
            "forms": [
                "generic-search-form",
                "filter-form",
                "bulk-actions"
            ],
            "cards": [
                "info-card",
                "stat-card",
                "mobile-record-card"
            ],
            "badges": [
                "status-badge",
                "popularity-badge",
                "generic-badge"
            ],
            "buttons": [
                "action-buttons",
                "crud-buttons",
                "filter-buttons"
            ],
            "layouts": [
                "page-wrapper",
                "content-section",
                "header-section"
            ],
            "form-fields": [
                "text-input",
                "number-input", 
                "select-input",
                "date-input",
                "checkbox-input",
                "textarea-input",
                "file-input"
            ],
            "widgets": [
                "pagination-widget",
                "loading-spinner",
                "no-data-widget",
                "confirmation-modal"
            ],
            "scripts": [
                "generic-table-manager",
                "form-validator",
                "ajax-handler"
            ]
        }
    }
    
    with open(os.path.join(base_dir, "partials", "component-manifest.json"), "w", encoding='utf-8') as f:
        json.dump(manifest, f, indent=2, ensure_ascii=False)
    
    print("\n" + "=" * 50)
    print("✅ Structure created successfully!")
    print("📄 Component manifest saved to: partials/component-manifest.json")
    print("\n📝 Next step: Run 'fill_components.py' to populate the components")
    
    return True

if __name__ == "__main__":
    try:
        create_directory_structure()
    except Exception as e:
        print(f"❌ Error creating structure: {str(e)}")
        exit(1)