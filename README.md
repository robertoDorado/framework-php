# PHP 7.4 Framework

This project is in version **7.4** and is configured to run with **Docker**. PHPUnit is already configured for unit testing.

## Requirements

- Docker and Docker Compose installed on your machine.

## Project installation on your machine

1. SSL installation in a local environment

This guide explains how to install `mkcert` on a Windows machine using Chocolatey.

2. Install Chocolatey. If you don't already have Chocolatey installed, follow the steps below:

3. Open PowerShell **as Administrator.** To do this, right-click the PowerShell icon and select **"Run as administrator".**

4. Run the following command to install Chocolatey:

    ```powershell
    Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
    ```

## Installing mkcert using Chocolatey

1. After installing Chocolatey, in the same PowerShell **with administrator permissions**, run the following command to install `mkcert`:

    ```powershell
    choco install mkcert -y
    ```

## Verification

To verify if `mkcert` was installed correctly, you can run:

   ```powershell
   mkcert -CAROOT
   ```

## Project Configuration

1. Clone this repository

   ```bash
   git clone https://github.com/robertoDorado/framework-php.git
   ```

2. After installing mkcert on your local machine, install the CA in the `ssl` folder

   ```powershell
   cd framework-php
   mkdir ssl
   cd ssl
   mkcert -install localhost
   ```

3. Run the project and update the dependencies

   ```docker
   docker-compose up -d
   docker exec -it php-apache-framework-php /bin/bash
   composer update
   ```

4. Run the migrations inside the container

   ```docker
   docker exec -it php-apache-framework-php /bin/bash
   dos2unix shell/migrations.sh
   chmod +x shell/migrations.sh
   shell/migrations.sh