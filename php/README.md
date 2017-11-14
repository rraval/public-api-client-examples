Encircle Public API: PHP sample
===============================

Sample code to create a claim using PHP and GRPC. The interesting part of the
code can be found in
[encircle_public_api_client.php](encircle_public_api_client.php).

Usage
-----

1. Install `pecl` and `phpize` (the linux package is generally called
   `php-pear`).
2. Install the `grpc` PHP extension with `sudo pecl install grpc`.
3. Add `extension=grpc.so` to your `/etc/php/php.ini` file so that the
   extension is loaded by PHP.
4. Install [Composer](https://getcomposer.org/).
5. Clone this repository.
6. Run `composer install` from this directory.
7. Run `php encircle_public_api_client.php ca.crt client.crt client.key`. Run
   it without any arguments to get the following usage information:

```
$ php encircle_public_api_client.php
Usage: php encircle_public_api_client.php CA_CERT CLIENT_CERT CLIENT_KEY [SERVER_URI]

CA_CERT is the full path to the Encircle CA certificate.

CLIENT_CERT is the full path to your client certificate. This is the signed
certificate generated for you based on the submitted CSR.

CLIENT_KEY is the full path to your client PRIVATE KEY. The previous 2 files
consist of public information but the contents of this file should be kept
secret and not shared with anyone!

SERVER_URI points to `api.encircleapp.com:50051` by default but can be used to
point to a different API server.
```

Here's what the expected output is supposed to look like if everything works:

```
$ php encircle_public_api_client.php ca.crt client.crt client.key
Creating Claim in org f77021ac-f731-405f-aab5-a5119a89658a with brand 1234
Created Claim with ID = 72317
```

Common Errors
-------------

### Class 'Grpc\ChannelCredentials' not found

PHP cannot load the `grpc` native extension. Double check that:

- You've run `sudo pecl install grpc`
- `extension=grpc.so` shows up in `/etc/php/php.ini` (or its equivalent).

Another way to check:

```
$ php -i | grep grpc
grpc
grpc support => enabled
```

If you don't see any `grpc` lines, the extension is not installed.

### require(): Failed opening required '.../vendor/autoload.php'

You did not install `composer` or fetch the required dependencies.

Simply `cd` into this directory and run `composer install`.

### E1113 ... Handshake failed with fatal error SSL_ERROR_SSL: error:1000007d:SSL routines:OPENSSL_internal:CERTIFICATE_VERIFY_FAILED.

You're using a bad Encircle CA certificate (the first file argument). Double
check that the certificate file matches the certificate the server is
providing with `openssl s_client`.

Here's what the output looks like with a good certificate:

```
$ openssl s_client -brief -CAfile ca.crt -connect 'api.encircleapp.com:50051'
write:errno=0
```

Here's what the output looks like with a bad certificate:

```
$ openssl s_client -brief -CAfile bad.crt -connect 'api.encircleapp.com:50051'
depth=0 CN = api.encircleapp.com
verify error:num=20:unable to get local issuer certificate
depth=0 CN = api.encircleapp.com
verify error:num=21:unable to verify the first certificate
write:errno=0
```

### E1113 ... Invalid private key.

As the error says, you provided a client certificate (the second argument) that
does not match the private key (the third argument).

### E1113 ... Invalid cert chain file.

The provided client certificate (the second argument) has not been signed by
the Encircle CA. Most likely, you passed in the path to the CSR instead of the
signed certificate that you received after submitting the CSR.

### E1113 ... Invalid private key.

The provided client key (third argument) is not recognized as a private key.

Open the file, it should start with a line that says:

```
-----BEGIN PRIVATE KEY-----
```

The private key is the secret file generated alongside the CSR and the one that
should never leave your possession.
