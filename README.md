# Raspberry IPTV #

Player simples para IPTV utilizando um Raspberry PI com Omxplayer (Testado com Raspberry PI 3).

### Instalando Omxplayer ###

* sudo apt-get update
* sudo apt-get install -y omxplayer

### Permissão necessária ###

Adicionar a linha no /etc/rc.local (antes do 'exit 0')

* sudo chmod 777 /dev/vchiq

Também rodar a linha no terminal para não precisar dar reboot na primeira execução.

### Instalar o apache e o php ###

* sudo apt-get install -y apache2 php7 # Ou a versao do PHP relativa do repositorio...

### Instalar cUrl ###

No linux:

* sudo apt-get install -y curl

No PHP:

* sudo apt-get install -y php-curl
* sudo service apache2 restart

### HotPlug do HDMI no Raspberry ###

Descomentar a linha abaixo do arquivo /boot/config.txt e se necessário, alterar o valor.

* hdmi_force_hotplug=1

### Clonar projeto ###

* cd /var/www/html
* sudo apt-get install -y git
* git clone https://wlopes@bitbucket.org/wlopes/raspberry-iptv.git . # Copiar com o . (ponto final), assim ele assume o diretório corrente.
* sudo chown www-data:www-data /var/www/html
* sudo chmod 777 -R /var/www/html

### Utilizar ###

* Acessar o IP do Raspberry pelo PC ou celular
* Clicar em atualizar (pode demorar um pouco)
* Escolher o canal ou arquivo para assistir

Recomendado atualizar com frequência para não ficar com canais offline.