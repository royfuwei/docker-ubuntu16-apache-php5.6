## Dockerfile make ubuntu16.04 apache php5.6


Dockerfile製作參考[1and1internet 的ubuntu-16-apache-php-5.6](https://github.com/1and1internet/ubuntu-16-apache-php-5.6)
並借用`files/`設定

### 主要流程
安裝docker、docker-compose、php版本、composer等
mac安裝docker會自動安裝，如果沒有安裝[install docker composer](https://docs.docker.com/compose/install/#install-compose)
本機端都裝好後，整包clone下來：
```sh
git clone <file>
cd <file>

# 如果要改port，到docker-compose.yml
docker-compose up -d

# 如果需要從dockerfile開始製作image，到docker-compose.yml，image改成build
docker-compose build
docker-compose up -d

cd www
composer install
```

### docker 以下分三種使用
1. 直接下載image使用
```sh
# 設定環境變數
UID=999
PORT=80
WEB_ROOT="/var/www/" # 只能使用絕對路徑

# 執行docker run
docker run -u ${UID}:0 -p ${PORT}:80 -v ${WEB_ROOT}:/var/www/ royfuwei/ubuntu16-apache-php5.6
```

2. 直接使用`docker-compose`執行，直接使用image
```sh
cd 檔案位置
# 執行
docker-compose up -d
```

3. 使用dockerfile設定，更改`docker-compose.yml`檔案，再執行`docker-compose`
如果php有需要做什麼設定，可以到`files/`、`Dockerfile`裡面做設定，然後更改一下`docker-compose.yml`:
`docker-compose.yml`更改：
```sh

```
docker 執行：
```sh 
docker-compose build
docker-compose up -d
```

### 開發檔案位置
如果直接下載image使用，開發的檔案位置會在設定的絕對路徑。
如果是用docker-compose執行，開發的檔案位置會在`www/`，一旦docker-compose設定好了，可以在檔案夾底下開發，但不要整個移動`www/`檔案位置
如果移動後無法出現網頁，重新執行container:
```sh
docker ps -a
docker restart <container id>
```
目前`www/`資料夾底下放slim jwt測試的網頁
`http://127.0.0.1`-首頁
`http://127.0.0.1/auth/token`-post 直接取得token
`http://127.0.0.1/auth/secure`-get header 帶`Authorization token`就可以訪問
`http://127.0.0.1/auth/not-secure`-get 不用header 帶`Authorization token`就可以訪問



___

Dockerfile製作參考[1and1internet 的ubuntu-16-apache-php-5.6](https://github.com/1and1internet/ubuntu-16-apache-php-5.6)

使用supervisor運行管理的ubuntu16.04
基於ubuntu:xenial，先自製加上supervisor運行管理的ubuntu，產生的自製ubuntu16 image。
後續基於自製ubuntu16 image，在dockerfile中安裝上apache的服務，編成新的ubuntu16-apache image，開啟的port是80,443，如果有需要針對apache做一些設定，可以在ubuntu16-apache的dockerfile以及files資料夾設定。
ubuntu16-apache image上，可以在dockerfile中安裝不同版本的php製成新的ubuntu16-apache-phpX.X iamge。


[1&1 Internet SE](https://github.com/1and1internet) 有許多的image可以提供參考
