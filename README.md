> ℹ️ Issues for this repository are tracked on [Phabricator](https://phabricator.wikimedia.org/project/board/5563/) - ([Click here to open a new one](https://phabricator.wikimedia.org/maniphest/task/edit/form/1/?tags=wikibase_cloud
))

# queryservice-gateway
A proxy for use in the WBStack suite of applications for Wikibase Cloud.

This acts as a reverse proxy using [redbird](https://github.com/OptimalBits/redbird) to route requests to a specific blazegraph namespace depending which wikibase sparql queries are targetting.

The blazegraph namepace is looked up from the [platform api](https://github.com/wbstack/api) and cached.
