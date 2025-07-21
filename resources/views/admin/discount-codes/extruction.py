import os

# الحصول على مسار الدليل الحالي الذي يحتوي على السكربت
current_dir = os.path.dirname(os.path.realpath(__file__))

def print_tree(directory, level=0, prefix=""):
   """طباعة شجرة المجلدات والملفات"""
   try:
       items = os.listdir(directory)
   except PermissionError:
       print(f"{prefix} [مجلد غير قابل للوصول]")
       return

   # تصفية العناصر المخفية
   items = [item for item in items if not item.startswith('.')]

   if not items:
       print(f"{prefix} [مجلد فارغ]")

   for i, item in enumerate(items):
       item_path = os.path.join(directory, item)
       is_last_item = i == len(items) - 1
       new_prefix = prefix + ("    " if is_last_item else "│   ")

       if os.path.isdir(item_path):
           print(f"{prefix}└── [{item}]")
           print_tree(item_path, level + 1, new_prefix)
       else:
           print(f"{prefix}└── {item}")

def write_files_content(directory, output_file, level=0):
   """استعراض الملفات وكتابة محتوياتها في ملف نصي"""
   try:
       items = os.listdir(directory)
   except PermissionError:
       output_file.write(f"{'  ' * level}[مجلد غير قابل للوصول]: {directory}\n\n")
       return

   items = [item for item in items if not item.startswith('.')]
   
   for item in items:
       item_path = os.path.join(directory, item)
       
       if os.path.isdir(item_path):
           output_file.write(f"{'  ' * level}📁 [{item}]\n")
           write_files_content(item_path, output_file, level + 1)
       else:
           output_file.write(f"{'  ' * level}📄 {item}\n")
           output_file.write(f"{'  ' * level}{'─' * 50}\n")
           
           try:
               with open(item_path, 'r', encoding='utf-8') as f:
                   content = f.read()
                   if content.strip():
                       output_file.write(content)
                   else:
                       output_file.write(f"{'  ' * level}[ملف فارغ]")
           except UnicodeDecodeError:
               try:
                   with open(item_path, 'r', encoding='latin-1') as f:
                       content = f.read()
                       output_file.write(content)
               except:
                   output_file.write(f"{'  ' * level}[ملف ثنائي - لا يمكن عرض المحتوى]")
           except Exception as e:
               output_file.write(f"{'  ' * level}[خطأ في قراءة الملف: {str(e)}]")
           
           output_file.write(f"\n{'  ' * level}{'─' * 50}\n\n")

# طباعة شجرة المجلدات أولاً
print("شجرة المجلدات والملفات:")
print("=" * 50)
print_tree(current_dir)
print("=" * 50)

# إنشاء ملف الإخراج
output_filename = os.path.join(current_dir, "files_content.txt")

print("\nجاري حفظ محتويات الملفات...")

with open(output_filename, 'w', encoding='utf-8') as output:
   output.write(f"محتويات الملفات في المجلد: {current_dir}\n")
   output.write("=" * 80 + "\n\n")
   write_files_content(current_dir, output)

print(f"تم حفظ محتويات الملفات في: {output_filename}")