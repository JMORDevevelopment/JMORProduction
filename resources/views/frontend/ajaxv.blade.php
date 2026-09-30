<div class="panel-group" id="accordion{{ $id }}" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne{{ $id }}">
            <h4 id="heading{{ $id }}" class="panel-title" style="background: #ada9a9;border-radius: 8px;">
            <span style="float: left; margin-top: 7px;margin-left: 7px;" class="fa fa-arrows-alt"></span><a id="hset{{ $id }}" role="button" data-toggle="collapse" data-parent="#accordion{{ $id }}" href="#collapseOne{{ $id }}" aria-expanded="true" aria-controls="collapseOne{{ $id }}">{{ $work_name }}</a></h4>
        </div>
        <div id="collapseOne{{ $id }}" class="panel-collapse collapse in show" role="tabpanel" aria-labelledby="headingOne{{ $id }}">
            <div class="panel-body">
                <div style="height: auto; border:none;" class="form-control">
<div id="no{{ $id }}" style="text-align: initial;display: ; ">
<Strong>Description: </Strong>  <p id="dset{{ $id }}">{{ $work_detail }}</p>

<Strong>Web Link: </Strong>        <p id="wset{{ $id }}">{{ $work_web }}</p>
</div>

                <div id="edit_v{{ $id }}" style="display: none;">
                <button type="button" onclick="save('{{ $id }}');" class="btn btn-primary" style="float: left;" >Save</button>
                <input name="work_name[]" id="work_name{{ $id }}" style="width: 100%;     margin-bottom: 20px;" type="text" class="p_style" value="{{ $work_name }}">

                <textarea name="work_detail[]" style="    margin-bottom: 20px;" id="detail{{ $id }}" rows="4" cols="50" type="text" value="" class="p_style form-control form-control-lg" placeholder="detail">{{ $work_detail }}</textarea>

                <input name="weblink[]"  id="web{{ $id }}" style="width: 100%;  margin-bottom: 20px;" type="text" class="p_style" value="{{ $work_web }}">

                </div>

                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <button onclick="remove_parent(this);" type="button" class="btn btn-danger fa fa-trash"></button>
                            <button  onclick="edit_parent(this,'{{ $id }}');" type="button" class="btn btn-primary fa fa-edit"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
