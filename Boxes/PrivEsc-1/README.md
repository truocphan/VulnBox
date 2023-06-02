# PrivEsc-1: Elementor Pro ≤ 3.11.6 - Authenticated(Subscriber+) Privilege Escalation via update_page_option
[![Buy Me a Coffee](https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png)](https://www.buymeacoffee.com/truocphan)

## Setting up the VulnBox_PrivEsc-1
Open `Docker Desktop` and then use the `Volumes Backup & Share` extension to Import 2 volumes in the `VulnBox/Boxes/PrivEsc-1/Volumes` folder:
```
- privesc-1_database_data.tar.zst
- privesc-1_wordpress_data.tar.zst
```

![image](https://user-images.githubusercontent.com/57470560/234558376-570881a1-17d4-4c87-8281-c40d8e1b04f5.png)

![image](https://user-images.githubusercontent.com/57470560/234558595-cb513a99-ec77-4766-8df4-9eb90d61b54a.png)

![image](https://user-images.githubusercontent.com/57470560/234558664-2e91b14f-5012-4b64-8665-ddc053bd597f.png)

To start `VulnBox_PrivEsc-1`, run the following commands:
```bash
cd VulnBox/Boxes/PrivEsc-1
./start_box.cmd
```
![image](https://user-images.githubusercontent.com/57470560/234558988-29e27076-9ae8-41f1-a148-4861701a0104.png)

Open a web browser and access the address http://127.0.0.1/. Log in with the account `subscriber/ subscriber`. Then, `enable registration` and change the default role to `Administrator`. Register a new account after changing the default role

![image](https://user-images.githubusercontent.com/57470560/234559216-2adebd64-e83c-4763-a2e8-c887e9b51e9e.png)

**Happy Hacking with VulnBox**

## [PoC Exploit](https://github.com/truocphan/VulnBox/tree/main#proof-of-concept-channel)
![image](https://user-images.githubusercontent.com/57470560/234559332-755fea09-c90d-49f6-ac69-50f5448a783c.png)
