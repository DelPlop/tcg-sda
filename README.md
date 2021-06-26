# Squelette Symfony

## Version de Symfony
actuellement, la version utilisée est la 5.3.2

## utilisation
### cloner le socle docker
`git clone git@github.com:DelPlop/docker-sf.git <nom projet>`

`cd <nom projet>`

créer le fichier `.env`

## cloner le squelette Symfony
`cd app`

`rm .gitignore`

`git clone git@gitlab.com:EliaLab/sf-skeleton.git .`

créer le fichier `.env`

## configurer le repo Git
`git remote set-url origin <url projet>`

éventuellement, modifier l'auteur et son email :

#### Gitlab :
`git config user.name "Elianora la blanche"`

`git config user.email "gitlab@phpblocnote.net"`

#### Github (default / global) :
`git config user.name "Del Plop"`

`git config user.email "github@phpblocnote.net"`
