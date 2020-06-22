<?php
/**
 * @var array $backups
 * @var string $url
 */
?>
<div class="box box-success box-solid">
    <div class="box-header">
        <h3 class="box-title"><?=Yii::t('dotenv', 'title')?></h3>
        <div class="box-tools"> </div>
    </div>
    <div class="box-body" id="app">
        <h1><a href="<?= $url . '/index'?>"><?=Yii::t('dotenv', 'title')?></a></h1>
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs">
                    <li v-for="view in views" role="presentation" :class="{ active : view.active }">
                        <a href="javascript:void(0);" @click="setActiveView(view.name)">{{ view.name }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <!-- Error-Container -->
                <div>
                    <div class="alert alert-warning" role="alert" v-show="alertwarning">
                        <button type="button" class="close" @click="closeWarning" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ alertmessage }}
                    </div>
                    <div class="alert alert-success" role="alert" v-show="alertsuccess">
                        <button type="button" class="close" @click="closeAlert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ alertmessage }}
                    </div>
                    <!-- Errors from POST-Requests -->
                    <?php if (Yii::$app->getSession()->hasFlash('dotenv')):?>
                    <div class="alert alert-success alert-dismissable" role="alert">
                        <button type="button" class="close" aria-label="Close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <?=Yii::$app->getSession()->getFlash('dotenv')?>
                    </div>
                    <?php endif;?>
                </div>
                <!-- Overview -->
                <div v-show="views[0].active">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title"> <?=Yii::t('dotenv', 'overview_title')?> </h2>
                        </div>
                        <div class="panel-body">
                            <p> <?=Yii::t('dotenv', 'overview_text')?> </p>
                            <p>
                                <a href="javascript:;" v-show="loadButton" class="btn btn-primary" @click="loadEnv">
                                    <?=Yii::t('dotenv', 'overview_button')?>
                                </a>
                            </p>
                        </div>
                        <div class="table-responsive" v-show="!loadButton">
                            <table class="table table-striped">
                                <tr>
                                    <th><?=Yii::t('dotenv', 'overview_table_key')?></th>
                                    <th><?=Yii::t('dotenv', 'overview_table_value')?></th>
                                    <th><?=Yii::t('dotenv', 'overview_table_options')?></th>
                                </tr>
                                <tr v-for="entry in entries">
                                    <td>{{ entry.key }}</td>
                                    <td>
                                        <i class="fa fa-eye" @click="entry.hide = !entry.hide"></i>
                                        <span>{{ entry.value | hide(entry.hide) }}</span>
                                    </td>
                                    <td>
                                        <a href="javascript:;" @click="editEntry(entry)" title="<?=Yii::t('dotenv', 'overview_table_popover_edit')?>">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                        </a>
                                        <a href="javascript:;" @click="modal(entry)" title="<?=Yii::t('dotenv', 'overview_table_popover_delete')?>">
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- Modal delete -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">{{ deleteModal.title }}</h4>
                                </div>
                                <div class="modal-body">
                                    <p><?=Yii::t('dotenv', 'overview_delete_modal_text')?></p>
                                    <p class="text text-warning">
                                        <strong>{{ deleteModal.content }}</strong>
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
	                                    <?=Yii::t('dotenv', 'overview_delete_modal_no')?>
                                    </button>
                                    <button type="button" class="btn btn-danger" @click="deleteEntry">
	                                    <?=Yii::t('dotenv', 'overview_delete_modal_yes')?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal edit -->
                    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">
	                                    <?=Yii::t('dotenv', 'overview_edit_modal_title')?>
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <strong><?=Yii::t('dotenv', 'overview_edit_modal_key')?>:</strong> {{ toEdit.key }}<br><br>
                                    <div class="form-group">
                                        <label for="editvalue"><?=Yii::t('dotenv', 'overview_edit_modal_value')?></label>
                                        <input type="text" v-model="toEdit.value" id="editvalue" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
	                                    <?=Yii::t('dotenv', 'overview_edit_modal_quit')?>
                                    </button>
                                    <button type="button" class="btn btn-primary" @click="updateEntry">
	                                    <?=Yii::t('dotenv', 'overview_edit_modal_save')?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Add new -->
                <div v-show="views[1].active">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title"><?=Yii::t('dotenv', 'addnew_title')?></h2>
                        </div>
                        <div class="panel-body">
                            <p>
	                            <?=Yii::t('dotenv', 'addnew_text')?>
                            </p>

                            <form @submit.prevent="addNew()">
                                <div class="form-group">
                                    <label for="newkey"><?=Yii::t('dotenv', 'addnew_label_key')?></label>
                                    <input type="text" name="newkey" id="newkey" v-model="newEntry.key" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="newvalue"><?=Yii::t('dotenv', 'addnew_label_value')?></label>
                                    <input type="text" name="newvalue" id="newvalue" v-model="newEntry.value" class="form-control">
                                </div>
                                <button class="btn btn-default" type="submit">
	                                <?=Yii::t('dotenv', 'addnew_button_add')?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Backups -->
                <div v-show="views[2].active">
                    <!-- Create Backup -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title">
	                            <?=Yii::t('dotenv', 'backup_title_one')?>
                            </h2>
                        </div>
                        <div class="panel-body">
                            <a href="/<?=$url?>/createbackup" class="btn btn-primary">
                            <?=Yii::t('dotenv', 'backup_create')?>
                            </a>
                            <a href="/<?=$url?>/download" class="btn btn-primary">
                            <?=Yii::t('dotenv', 'backup_download')?>
                            </a>
                        </div>
                    </div>

                    <!-- List of available Backups -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title"><?=Yii::t('dotenv', 'backup_title_two')?></h2>
                        </div>
                        <div class="panel-body">
                            <p>
	                            <?=Yii::t('dotenv', 'backup_restore_text')?>
                            </p>
                            <p class="text-danger">
	                            <?=Yii::t('dotenv', 'backup_restore_warning')?>
                            </p>
                            <?php if (!$backups):?>
                            <p class="text text-info">
	                            <?=Yii::t('dotenv', 'backup_no_backups')?>
                            </p>
                            <?php endif;?>
                        </div>
	                    <?php if ($backups):?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <th><?=Yii::t('dotenv', 'backup_table_nr')?></th>
                                    <th><?=Yii::t('dotenv', 'backup_table_date')?></th>
                                    <th><?=Yii::t('dotenv', 'backup_table_options')?></th>
                                </tr>
								<?php $c = 1;?>
                                <?php foreach ($backups as $backup):?>
                                <tr>
                                    <td><?=$c++?></td>
                                    <td><?=$backup['formatted']?></td>
                                    <td>
                                        <a class="btn btn-success" href="javascript:;" @click="showBackupDetails('<?=$backup['unformatted']?>', '<?=$backup['formatted']?>')" title="<?=Yii::t('dotenv', 'backup_table_options_show')?>">
                                            <span class="fa fa-search-plus"></span>
                                        </a>
                                        <a class="btn btn-warning" href="javascript:;" @click="restoreBackup('<?=$backup['unformatted']?>')" title="<?=Yii::t('dotenv', 'backup_table_options_restore')?>">
                                            <span class="fa fa-refresh" title="<?=Yii::t('dotenv', 'backup_table_options_restore')?>"></span>
                                        </a>
                                        <a class="btn btn-info" href="<?=$url . '/download/' . $backup['unformatted']?>">
                                        <span class="fa fa-download" title="<?=Yii::t('dotenv', 'backup_table_options_download')?>"></span>
                                        </a>
                                        <a onclick="return confirm('<?=Yii::t('dotenv', 'warning_operating')?>')" class="btn btn-danger" href="<?= $url.'/deletebackup/'.$backup["unformatted"]?>" title="<?=Yii::t('dotenv', 'backup_table_options_delete')?>">
                                        <span class="fa fa-trash"></span>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </table>
                        </div>
	                    <?php endif;?>
                    </div>

                    <?php if ($backups):?>
                    <!-- Details Modal -->
                    <div class="modal fade" id="showDetails" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"><?=Yii::t('dotenv', 'backup_modal_title')?></h4>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-striped">
                                        <tr>
                                            <th><?=Yii::t('dotenv', 'backup_modal_key')?></th>
                                            <th><?=Yii::t('dotenv', 'backup_modal_value')?></th>
                                        </tr>
                                        <tr v-for="entry in details">
                                            <td>{{ entry.key }}</td>
                                            <td>{{ entry.value }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <a href="javascript:;" @click="restoreBackup(currentBackup.timestamp)" title="Stelle dieses Backup wieder her" class="btn btn-primary">
	                                    <?=Yii::t('dotenv', 'backup_modal_restore')?>
                                    </a>

                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=Yii::t('dotenv', 'backup_modal_close')?></button>

                                    <a onclick="return confirm('<?=Yii::t('dotenv', 'warning_operating')?>')" href="<?= $url . '/deletebackup/'.$backup["unformatted"]?>" class="btn btn-danger">
                                    <?=Yii::t('dotenv', 'backup_modal_delete')?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
                </div>
                <!-- Upload -->
                <div v-show="views[3].active">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title"><?=Yii::t('dotenv', 'upload_title')?></h2>
                        </div>
                        <div class="panel-body">
                            <p>
	                            <?=Yii::t('dotenv', 'upload_text')?><br>
                                <span class="text text-warning">
                                    <?=Yii::t('dotenv', 'upload_warning')?>
                                </span>
                            </p>
                            <form method="post" action="<?= $url . '/upload'?>" enctype="multipart/form-data">
                            <?php
                            use yii\helpers\Html;
                            $request = Yii::$app->getRequest();
                            echo Html::hiddenInput($request->csrfParam, $request->getCsrfToken());
                            ?>
                            <div class="form-group">
                                <label for="backup"><?=Yii::t('dotenv', 'upload_label')?></label>
                                <input type="file" name="backup">
                            </div>
                            <button type="submit" class="btn btn-primary" title="Ein Backup von deinem Computer hochladen">
	                            <?=Yii::t('dotenv', 'upload_button')?>
                            </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- box body -->
</div> <!-- box -->
<?php /** @var \yii\web\View $this */?>
<?php $this->beginBlock('dotenv-default-index') ?>
<script>
new Vue({
    el: '#app',
    data: function () {
        return {
            loadButton: true,
            alertsuccess: 0,
            alertwarning:0,
            alertmessage: '',
            views: [
                {name: "<?=Yii::t('dotenv', 'overview')?>", active: 1},
                {name: "<?=Yii::t('dotenv', 'addnew')?>", active: 0},
                {name: "<?=Yii::t('dotenv', 'backups')?>", active: 0},
                {name: "<?=Yii::t('dotenv', 'upload')?>", active: 0}
            ],
            newEntry: {
                key: "",
                value: ""
            },
            details: {},
            currentBackup: {
                timestamp: ''
            },
            toEdit: {},
            toDelete: {},
            deleteModal: {
                title: '',
                content: ''
            },
            token: "<?=Yii::$app->request->getCsrfToken()?>",
            entries: [
            ]
        }
    },
    filters: {
        hide: function(value, hide) {
            if (hide) {
                return '*'.repeat(value.length)
            }
            return value
        }
    },
    methods: {
        loadEnv: function(){
            var vm = this;
            this.loadButton = false;
            $.getJSON("/<?=$url?>/getdetails", function(items){
                vm.entries = items.map(item => {
                    item.hide = false
                    if (
                        item.key.toLowerCase().includes('key') ||
                        item.key.toLowerCase().includes('secret') ||
                        item.key.toLowerCase().includes('password')
                    )
                    {
                        item.hide = true
                    }
                    return item
                });
            });
        },
        setActiveView: function(viewName){
            $.each(this.views, function(key, value){
                if(value.name == viewName){
                    value.active = 1;
                } else {
                    value.active = 0;
                }
            })
        },
        addNew: function(){
            var vm = this;
            var newkey = this.newEntry.key;
            var newvalue = this.newEntry.value;
            $.ajax({
                url: "/<?=$url?>/add",
                type: "post",
                data: {
	                "<?=Yii::$app->request->csrfParam?>": this.token,
                    key: newkey,
                    value: newvalue
                },
                success: function(json){
                    vm.entries = json.env
                    if (json.warning) {
                        vm.showWarning(json.warning)
                    } else {
                        var msg = "<?=Yii::t('dotenv', 'new_entry_added')?>";
                        vm.showAlert("success", msg);
                    }
                    $("#newkey").val("");
                    vm.newEntry.key = "";
                    vm.newEntry.value = "";
                    $("#newvalue").val("");
                    $('#newkey').focus();
                }
            })
        },
        editEntry: function(entry){
            this.toEdit = {};
            this.toEdit = entry;
            $('#editModal').modal('show');
        },
        updateEntry: function(){
            var vm = this;
            $.ajax({
                url: "/<?=$url?>/update",
                type: "post",
                data: {
                    "<?=Yii::$app->request->csrfParam?>": this.token,
                    key: vm.toEdit.key,
                    value: vm.toEdit.value
                },
                success: function(){
                    var msg = "<?=Yii::t('dotenv', 'entry_edited')?>";
                    vm.showAlert("success", msg);
                    $('#editModal').modal('hide');
                }
            })
        },
        makeBackup: function(){
            var vm = this;
            $.ajax({
                url: "/<?=$url?>/createbackup",
                type: "get",
                success: function(){
                    vm.showAlert('success', "<?=Yii::t('dotenv', 'backup_created')?>");
                    vm.setActiveView('<?=Yii::t('dotenv', 'backups')?>')
                }
            })
        },
        showBackupDetails: function(timestamp, formattedtimestamp){
            this.currentBackup.timestamp = timestamp;
            var vm = this;
            $.getJSON("/<?=$url?>/getdetails/" + timestamp, function(items){
                vm.details = items;
                $('#showDetails').modal('show');
            });
        },
        restoreBackup: function(timestamp){
            var vm = this;
            if (confirm("<?=Yii::t('dotenv', 'warning_operating')?>")) {
                $.ajax({
                    url: "/<?=$url?>/restore/" + timestamp,
                    type: "get",
                    success: function(){
                        vm.loadEnv();
                        $('#showDetails').modal('hide');
                        vm.setActiveView('<?=Yii::t('dotenv', 'overview')?>');
                        vm.showAlert('success', "<?=Yii::t('dotenv', 'backup_restored')?>");
                    }
                })
            }
        },
        deleteEntry: function(){
            var entry = this.toDelete;
            var vm = this;
            $.ajax({
                url: "/<?=$url?>/delete",
                type: "post",
                data: {
                    "<?=Yii::$app->request->csrfParam?>": this.token,
                    key: entry.key
                },
                success: function(){
                    var msg = "<?=Yii::t('dotenv', 'entry_deleted')?>";
                    vm.showAlert("success", msg);
                }
            });
            var index = this.entries.indexOf(entry)
            this.entries.splice(index, 1);
            this.toDelete = {};
            $('#deleteModal').modal('hide');
        },
        showAlert: function(type, message){
            this.alertmessage = message;
            this.alertsuccess = 1;
            this.alertwarning = 0;
        },
        closeAlert: function(){
            this.alertsuccess = 0;
        },
        showWarning: function (message) {
            this.alertmessage = message;
            this.alertsuccess = 0;
            this.alertwarning = 1;
        },
        closeWarning: function(){
            this.alertwarning = 0;
        },
        modal: function(entry){
            this.toDelete = entry;
            this.deleteModal.title = "<?=Yii::t('dotenv', 'delete_entry')?>";
            this.deleteModal.content = entry.key + "=" + entry.value;
            $('#deleteModal').modal('show');
        }
    }
})
$(document).ready(function(){
    $(function () {
        $('[data-toggle="popover"]').popover()
    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
})
</script>
<?php $this->endBlock()?>
<?php
$this->blocks['dotenv-default-index'] = str_replace(["<script>", "</script>"], "", $this->blocks['dotenv-default-index']);
$this->registerJs($this->blocks['dotenv-default-index'], \yii\web\View::POS_END)
?>
