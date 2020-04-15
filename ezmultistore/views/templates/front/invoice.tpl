<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {l s='Invoice : Order N°' mod='ezmultistore'}{$id_order}</title>
</head>
<body id="target">
    <div id="content">
        <h2>Test</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae consectetur corporis dolorem, error, est magnam natus nobis qui quia, quis quo recusandae sapiente unde voluptates voluptatum! Itaque nihil saepe similique.</p>
    </div>
    <button id="cmd">{l s='Generate PDF' mod='ezmultistore'}</button>
</body>

{literal}
    <script type="text/javascript">
        $(document).ready(function () {

            var specialElementHandler = {
                "#editor":function (element,renderer) {
                    return true;
                }
            };

            $("#cmd").click(function () {

                var doc = new JsPDF();
                doc.fromHTML($("#target").html(),15,15, {
                    "width":170,
                    "elementHandlers":specialElementHandler,
                })
                doc.save(document.title+".pdf")

            });
        });

    </script>
{/literal}
</html>