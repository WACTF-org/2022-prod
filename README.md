# 2022-prod

WACTF 0x05 Production!

This repository is **unmaintained** and its contents are provided AS IS. Some challenges have been removed, some may no longer work, documentation may be inaccurate, etc. No support will be provided but PRs may be merged if you submit a bugfix :)

Contents:
* `ChallengeDocumentation` contains `.md` files with the challenge description, hints, flag, and expected method to solve. WACTF specific DNS names in these files (e.g. "access the challenge at: `http://web-0`") will not work by default, access the challenges via their IP address instead.
* `ChallengeFiles` contains the challenge code and `docker-compose.yaml` file. Running `docker compose up` inside this directory should bring up the game. If any one container fails, the whole deployment will fail. Comment out offending challenges as necessary in the docker-compose file.
* `FileDrop` contains the supporting files for challenges that required them.
* `support` contains the `dns-check` container which was used during the game for ensuring player's VPN was configured correctly.
