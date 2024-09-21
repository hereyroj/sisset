<div class="row">
    <div class="col-md-6">
        <h3>Ahora</h3>
        <div class="well">
            <pre>
                <?php
                if (isset($actividad->changes()["attributes"])) {
                    print_r($actividad->changes()["attributes"]);
                }
                ?>
            </pre>
        </div>
    </div>
    <div class="col-md-6">
        <h3>Antes</h3>
        <div class="well">
            <pre>
                <?php
                if (isset($actividad->changes()["old"])) {
                    print_r($actividad->changes()["old"]);
                }
                ?>
            </pre>
        </div>
    </div>
</div>