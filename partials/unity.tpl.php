<!-- this will be replaced by markdown templates, when I can embed these things more gracefully -->
<div class="modal-body">
        <script type="text/javascript">
        <!--
        var config = { width: 960, height: 600,
                params: { enableDebugging:"0" }
        };
        var u = new UnityObject2(config);
        jQuery(function() {
                u.initPlugin(jQuery("#unityPlayer")[0], "<?php echo $_GET["src"]; ?>");
        });
        -->
        </script>
        <a class="close"
        ng-click="closePartial()"></a>
        <div id="unityPlayer" style="margin: auto; cursor: default; height: 600px; width: 960px;"></div>
</div>
