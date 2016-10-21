# nonce
Simple custom nonce token generator to prevent string or URL modifications

## Installation
To install, add code below in your **composer.json**:
```
{
  "require" : {
    "php" : ">=5.3.0",
    "Zenich75/nonce" : "dev-master"
  },
  "repositories":[
    {
      "type":"git",
      "url":"https://github.com/Zenich75/nonce"
    }
  ]
}
```
and run `composer update` from console.

## Get nonce
For creating nonce you should create object `Nonce` and transmit string to function `createNonce` as argument.
In return you get string with nonce of incoming string.
If you need nonce as url parameter, you can use function `nonceUrl`. It has two arguments string for creating nonce and optional argument **nonceParamName**.
It returns string with url part like **_nonce=XXXXXXXXXXXXX** where XXXXXXXXXXXXX is nonce. If was transmitted optional argument - it would be shown instead *_nonce*

## Check existed nonce
For checking you should transmit checking string (or url) and nonce separately into function `checkNonce`. In return you boolean result of the comparison.

## Initialization
```
try {
    $nonceObj = new Nonce($salt, $length, $lifetime, $nonceParamName);
} catch (\Exception $error) {
    echo $error->getMessage();
}
```
$salt - Required parameter, set secret salt for nonce (minimum length - 10 symbols)

$length - Optional parameter which sets the length of the nonce string in URL (default - 10)

$lifetime - Optional parameter which sets lifetime of nonce in seconds (default - 3600 (1 hour))

$nonceParamName - Optional parameter which sets URL parameter name for nonce (default - '_nonce')


## Functions
### createNonce ($string)
Create nonce from string
          
### checkNonce ($nonce, $string)
Comparison of the received and the expected nonce

### nonceUrl ($string)
Return nonce as url part

### setParamName($nonceParamName)
Set URL parameter name for nonce

### getParamName()
Get URL parameter name for nonce
