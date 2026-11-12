<div class="ts-work-card" id="accordion{{ $id }}">
    <div class="ts-work-card__head">
        <span class="ts-work-card__title"><i class="fa fa-television" aria-hidden="true"></i> Entry {{ $id }}</span>
        <button type="button" class="ts-work-card__remove" onclick="remove_parent(this);"
                aria-label="Remove entry {{ $id }}">
            <i class="fa fa-trash" aria-hidden="true"></i> Remove
        </button>
    </div>
    <div class="ts-work-card__grid">
        <div>
            <label for="work_name{{ $id }}">Show or Experience <span class="text-danger" aria-hidden="true">*</span></label>
            <input type="text" name="work_name[]" id="work_name{{ $id }}" class="form-control"
                   value="{{ $work_name }}" maxlength="255" required>
        </div>
        <div>
            <label for="detail{{ $id }}">Description</label>
            <textarea name="work_detail[]" id="detail{{ $id }}" class="form-control" rows="2"
                      maxlength="500">{{ $work_detail }}</textarea>
        </div>
        <div>
            <label for="web{{ $id }}">Web Link <small class="text-muted">(optional)</small></label>
            <input type="url" name="weblink[]" id="web{{ $id }}" class="form-control"
                   value="{{ $work_web }}" placeholder="https://">
        </div>
    </div>
</div>
