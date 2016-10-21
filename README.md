# nonce
Simple custom nonce token generator to prevent string or URL modifications

## Get nonce
For creating nonce you should create object `Nonce` and transmit string to function `createNonce` as argument.
In return you get string with nonce of incoming string.
If you need nonce as url parameter, you can use function `nonceUrl`. It has two arguments string for creating nonce and optional argument **nonceParamName**.
It returns string with url part like **_nonce=XXXXXXXXXXXXX** where XXXXXXXXXXXXX is nonce. If was transmitted optional argument - it would be shown instead *_nonce*

## Check existed nonce
For checking you should transmit checking string (or url) and nonce separately into function `checkNonce`. In return you boolean result of the comparison.
