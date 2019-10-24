# ssh-vault

Lightweight utility for ssh connections management

## How it works
It just parses and modifies `~/.ssh/config` file. You can continue to use tools that you like and just use this wrapper to add or remove connections from your ssh config file.

## How to use
### Add Host
```sh
# start a short setup wizard that reuses your past history
> ssh-vault add
```
### List Hosts
```sh
> ssh-vault list
```

### Remove Host
```sh
# Remove connection by its index from a list
> ssh-vault remove
```

### Print raw ssh config content
```sh
# Just prints raw content of ~/.ssh/config
> ssh-vault raw
```

## SSH config
SSH config file is super simple. It just stores information about your servers and how to connect to them. Imagine that you have a `production` server with IP `192.168.10.10`. After you add it to your ssh config file

```sh
Host production
  HostName 192.168.10.10
  User local
  ForwardAgent yes
```
you can connect to it just by using this command:
```sh
ssh production
```
That's it. No more bash aliases, shell history, txt notes or whatever people usually do to store server details.
