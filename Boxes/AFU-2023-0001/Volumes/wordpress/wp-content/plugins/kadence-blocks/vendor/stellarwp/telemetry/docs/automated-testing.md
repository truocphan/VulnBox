# Automated testing

This repository uses Codeception for automated testing and is configured to use [`slic`](https://github.com/stellarwp/slic) to run them. Please follow the instructions over there in order to get slic up and running on your machine.

## Running the Tests

Once Slic is running on your machine, go to the parent directory of the telemetry library and run `slic here`. To use the telemetry library, run `slic use telemetry`.

## Run the tests

```bash
slic run wpunit
```

Or go into `slic shell` (this is faster) and run the tests:

```bash
slic shell

# Your prompt will look like this: ".../plugins/schema > "
# Run the following command to run the wpunit tests:
cr wpunit

# To leave slic shell:
exit
```
