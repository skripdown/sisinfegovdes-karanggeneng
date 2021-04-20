import shutil
import os

directory = 'storage/app/public/archive'
shutil.rmtree(directory)
os.mkdir(directory)
