<div id="form_fields">
    <draggable class="dragArea list-group"
               :list="fields"
               :clone="cloneField"
               :group="{ name: 'field', pull: 'clone', put: false }">
        <div class="list-group-item" v-for="field in fields">
            {{field.field_name}}
            <i class="fas fa-arrows-alt"></i>
        </div>
    </draggable>
</div>