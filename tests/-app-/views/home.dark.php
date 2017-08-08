<dark:use path="pieces/piece" as="pieces:piece"/>
<dark:use path="pieces/meta" as="pieces:meta"/>

<!doctype html>
<html>
<head>
    <pieces:meta title="Title" description="Description" keywords="Keywords">
        <meta name="foo" content="Bar">
    </pieces:meta>
</head>
<body>
<pieces:piece name="sample-piece">
    Sample home template.
</pieces:piece>
</body>
</html>