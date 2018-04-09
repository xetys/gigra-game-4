# GIGRA GAME

This is my browsergame I coded from 2009 - 2013 written in PHP. It was operating mainly from 2011 - 2013.

This code is now online as a piece of my coding history and servers an opportunity for users to make
own game updates on http://gigra-game.de

## Über diese Repo

Dies ist die offizielle Repository für das alte nostalgische Browsergame Gigra. 
Es läuft (ggf.) auf http://gigra-game.de und kann dort aktiv gespielt werden. 

Da ich zum heutigen Tage keine Zeit habe, das Spiel weiterzuentwickeln, biete ich mit 
dieser freien Version die Option, dass Spieler selbst Updates für das Spiel schreiben 
können. Damit kann bei Interesse, die Weiterentwicklung des Spiels bestehen.


## Weiterentwicklung

Wenn sich jemand in der Lage sieht, den Code zu verändern um Fehler zu beheben oder auch Features zu verbauen
kann dieser Repo via GitHub einen Pull Request anbieten. Nach Prüfung meinerseits kann dieser PR dann in den 
Master aufgenommen und auf die laufende Runde installiert werden.

Hierdurch erhalten die freiwilligen Entwickler keinen Datenzugriff auf die interne Datenbank, womit man sowohl 
spielen als auch entwickeln darf (was früher gegensätzlich ausschließend war).


## Installation

Um an dem Spiel arbeiten zu können, wird lediglich ein System mit [docker](https://www.docker.com/) benötigt, sowie
[docker-compose](https://docs.docker.com/compose/).

Das Spiel kann durch folgenden Befehl im Hauptordner gestartet werden:

```bash
docker-compose -f docker-compose-dev.yaml up -d
```

Sobald alle images gezogen worden und gestartet worden sind, kann man sich unter http://localhost eingeloggt werden.

#### Zugangsdaten

**Spielername**: admin

**Passwort**: admin



