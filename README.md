DO IP Updater
---------------

Allows the dynamic updating of an 'A' or 'AAAA' record that is managed by Digital Ocean's DNS servers.


## installation

Download the phar file

```bash

```

```bash
mv ip-update.phar ip-updater 
```

## Usage
 
First set your DigitalOcean API token as en env var before you can run the command.

```bash
$ set DO_API_TOKEN=YourTokenHere
```

Most simple way is to just pass it the domain and record name that need to be updated, if its not a sub 
domain it is usually named `@`.

This will then update the ipv4 (`A` type) record named `@` for domain your-domain.com

```bash
ip-updater ip:update your-domain.com @
```

If you want to update the ipv6  (`AAAA` type) record you pass the `--ipv 6` option.

```bash
ip-updater ip:update yourdomain.com @ --ipv 6
```

or update both types at once with the `--all` flag

```bash
ip-updater ip:update yourdomain.com @ --all
```
