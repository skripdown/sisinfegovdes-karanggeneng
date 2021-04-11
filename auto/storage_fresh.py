import os


def rem(path, directory):
    for dirs in directory:
        temp = path + dirs
        for f in os.listdir(temp):
            os.remove(os.path.join(temp, f))


root = 'storage/app/public/'
adm_path = 'admin/'
ctz_path = 'citizen/'
adm_dir = ['profile']
ctz_dir = ['profile', 'card']
rem(root + adm_path, adm_dir)
rem(root + ctz_path, ctz_dir)
