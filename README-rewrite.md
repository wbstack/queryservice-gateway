This probably 100% doesn't want to be written in PHP, but that will work as a proof of concept for now..

Probably want to write it in Go or JS? Or hooking into nginx somehow?

JS http-proxy:
 - Full customizability / logic https://www.npmjs.com/package/http-proxy#setup-a-stand-alone-proxy-server-with-custom-server-logic
 - Altering response https://www.npmjs.com/package/http-proxy#modify-response

JS express-http-proxy:
 - https://www.npmjs.com/package/express-http-proxy
 - Host can be selected based on arb function (search does for "selectProxyHost")
 - Altering request https://www.npmjs.com/package/express-http-proxy#proxyreqoptdecorator--supports-promise-form
 - Altering response https://www.npmjs.com/package/express-http-proxy#userresdecorator-was-intercept-supports-promise

JS redbird:
 - https://www.npmjs.com/package/redbird
 - Looks cool, but not sure it supports rewriting responses (requests maybe)
 - Custom host resolvers are supported https://www.npmjs.com/package/redbird#defining-resolvers
  - This includes looking up records from etcd etc..
