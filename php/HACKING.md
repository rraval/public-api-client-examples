This repository checks in `protoc` generated files, which is not strictly
necessary but simplifies things for downstream consumers.

To regenerate these files:

### Build the GRPC PHP generator plugin

Clone the repo:

```
$ git clone https://github.com/grpc/grpc
```

Build the plugin:

```
$ cd grpc
grpc $ make grpc_php_plugin
```

Remember the path to the git clone, it's needed for `protoc` to find the extension.

### Regenerate the client

Switch to this git repo:

```
$ cd public-api-client-examples
```

Use `protoc` to regenerate the client. `$GRPC_CHECKOUT` is the path to the git clone of `grpc`.

```
public-api-client-examples $ protoc \
    --php_out=php \
    --grpc_out=php \
    --plugin=protoc-gen-grpc=$GRPC_CHECKOUT/bins/opt/grpc_php_plugin \
    encircle_public_api_v1.proto
```
