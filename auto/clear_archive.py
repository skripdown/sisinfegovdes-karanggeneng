import os

directory = 'storage/app/public/archive'
for f in os.listdir(directory):
    os.rmdir(os.path.join(directory, f))
