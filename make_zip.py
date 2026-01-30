
import zipfile
import os

def zip_folder(folder_path, output_path):
    with zipfile.ZipFile(output_path, 'w', zipfile.ZIP_DEFLATED) as zipf:
        for root, dirs, files in os.walk(folder_path):
            for file in files:
                file_path = os.path.join(root, file)
                # Create a relative path for the zip entry
                arcname = os.path.relpath(file_path, os.path.dirname(folder_path))
                # Ensure forward slashes for compatibility
                arcname = arcname.replace(os.path.sep, '/')
                zipf.write(file_path, arcname)
            
            # Also add entries for empty directories if necessary, 
            # though standard walking usually covers files. 
            # If we strictly need directory entries:
            for d in dirs:
                 dir_path = os.path.join(root, d)
                 arcname = os.path.relpath(dir_path, os.path.dirname(folder_path))
                 arcname = arcname.replace(os.path.sep, '/') + '/'
                 zipinfo = zipfile.ZipInfo(arcname)
                 zipf.writestr(zipinfo, '')

if __name__ == "__main__":
    source_folder = "d:\\wordpress\\bestonwardticket-theme"
    output_zip = "d:\\wordpress\\theme-upload-fix.zip"
    
    print(f"Zipping {source_folder} to {output_zip}...")
    try:
        zip_folder(source_folder, output_zip)
        print("Zip created successfully.")
    except Exception as e:
        print(f"Error creating zip: {e}")
