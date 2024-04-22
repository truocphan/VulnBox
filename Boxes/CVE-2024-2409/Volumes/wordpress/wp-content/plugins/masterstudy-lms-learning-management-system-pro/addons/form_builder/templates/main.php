<div id="stm_lms_form_builder">
	<div class="loading" v-if="loading"></div>
	<div class="form_builder_title">
		<div v-if="typeof forms[currentForm] !== 'undefined'" ref="dropdownMenu" :class="openSelect ? 'form-select-wrap active' : 'form-select-wrap'" @click="toggleSelect()">
			<div class="form-select-label">
				<span>{{forms[currentForm].name}}</span>
				<i class="fas fa-chevron-circle-down"></i>
			</div>
			<div class="form-select">
				<div v-for="(form, formIndex) in forms" :class="currentForm == formIndex ? 'checked' : ''" @click="currentForm = formIndex" v-html="form.name"></div>
			</div>
		</div>
		<div class="form-buttons-wrap">
			<button @click="getForms()" class="cancel-button">
				<i class="fas fa-times-circle"></i>
				<?php esc_html_e( 'Cancel Changes', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</button>
			<button @click="saveForms()" class="button-primary button">
				<?php esc_html_e( 'Save Changes', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</button>
		</div>
	</div>

	<div class="stm_lms_form_builder__row">
		<div class="fields" v-if="typeof forms[currentForm] !== 'undefined'">
			<div class="fields-area">
				<div class="required-fields dragArea" v-if="typeof requiredFields[forms[currentForm].slug] !== 'undefined'">
					<div class="list-group">
						<div :class="key === currentField ? 'list-group-item active' : 'list-group-item'" v-for="(requiredField, key) in requiredFields[forms[currentForm].slug]" @click="changeCurrentField(key)">
							<span class="field-title" v-html="requiredField.label"></span>
							<span class="field-hint"><?php esc_html_e( 'Default field', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
						</div>
					</div>
				</div>
				<draggable
						:class="forms[currentForm]['fields'].length <= 0 ? 'dragArea list-group empty' : 'dragArea list-group'"
						:list="forms[currentForm]['fields']"
						group="field"
						@add="afterDrag"
						data-empty-text="<?php esc_attr_e( 'Drag Elements Here', 'masterstudy-lms-learning-management-system-pro' ); ?>">
					<div :class="currentField === field_index ? 'active list-group-item' : 'list-group-item'" v-for="(field, field_index) in forms[currentForm]['fields']">
						<div class="field-controls">
							<i class="fas fa-clone" @click="duplicateField(field)"></i>
							<i class="fas fa-times" @click="deleteField(field_index)"></i>
						</div>
						<div @click="changeCurrentField(field_index)">
							<div class="field-title">
								<div class="field-label" v-html="typeof field.label !== 'undefined' ? field.label : field.field_name"></div>
								<div class="field-type" v-html="field.field_name"></div>
							</div>
							<component :is="'stm_lms_forms_' + field.type"
									:key="field.id"
									v-on:get-field="$set(forms[currentForm]['fields'], field_index, $event)"
									:field_data="field">
							</component>
							<div class="field-description" v-if="typeof field.description !== 'undefined'" v-html="field.description"></div>
						</div>
					</div>
				</draggable>
			</div>
			<div class="field-info">
				<div class="field-settings" v-if="typeof forms[currentForm] !== 'undefined' && typeof requiredFields[forms[currentForm].slug] !== 'undefined' && typeof requiredFields[forms[currentForm].slug][currentField] !== 'undefined'">
					<div class="field-setting add-to-register" v-if="forms[currentForm].slug === 'profile_form'">
						<label class="switcher-label">
							<div class="switcher-wrap">
								<input type="checkbox" v-model="requiredFields[forms[currentForm].slug][currentField].register"/>
								<span class="switcher">
									<span class="text-label"></span>
								</span>
							</div>
							<span class="field-title">
								<?php esc_html_e( 'Show on Registration form', 'masterstudy-lms-learning-management-system-pro' ); ?>
								<span class="tooltip-wrap">
									<i class="fas fa-info-circle"></i>
									<span class="field-tooltip"><?php esc_html_e( 'If you check this option, this field will be added to the registration form as well', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
								</span>
							</span>
						</label>
					</div>
					<div class="field-setting">
						<label>
							<span class="field-title">
								<?php esc_html_e( 'Required field', 'masterstudy-lms-learning-management-system-pro' ); ?>
								<span class="tooltip-wrap">
									<i class="fas fa-info-circle"></i>
									<span class="field-tooltip"><?php esc_html_e( 'Mark the field as required', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
								</span>
							</span>
							<input type="checkbox" v-model="requiredFields[forms[currentForm].slug][currentField].required"/>
						</label>
					</div>
					<p>This field is a default field for the user profile in the MasterStudy LMS. It can not be changed or deleted.</p>
				</div>
				<div class="field-settings" v-if="typeof forms[currentForm]['fields'][currentField] !== 'undefined'">
					<div class="field-setting add-to-register" v-if="forms[currentForm].slug === 'profile_form'">
						<label class="switcher-label">
							<div class="switcher-wrap">
								<input type="checkbox" v-model="forms[currentForm]['fields'][currentField].register"/>
								<span class="switcher">
									<span class="text-label"></span>
								</span>
							</div>
							<span class="field-title">
								<?php esc_html_e( 'Show on Registration form', 'masterstudy-lms-learning-management-system-pro' ); ?>
								<span class="tooltip-wrap">
									<i class="fas fa-info-circle"></i>
									<span class="field-tooltip"><?php esc_html_e( 'If you check this option, this field will be added to the registration form as well', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
								</span>
							</span>
						</label>
					</div>
					<div class="field-setting">
						<label>
							<span class="field-title">
								<?php esc_html_e( 'Field Name', 'masterstudy-lms-learning-management-system-pro' ); ?>
								<span class="tooltip-wrap">
									<i class="fas fa-info-circle"></i>
									<span class="field-tooltip"><?php esc_html_e( 'Enter a unique field name for this form', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
								</span>
							</span>
							<input type="text" v-model="forms[currentForm]['fields'][currentField].slug"/>
						</label>
					</div>
					<div class="field-setting">
						<label>
							<span class="field-title">
								<?php esc_html_e( 'Field Label', 'masterstudy-lms-learning-management-system-pro' ); ?>
								<span class="tooltip-wrap">
									<i class="fas fa-info-circle"></i>
									<span class="field-tooltip"><?php esc_html_e( 'Enter the field slug which will be displayed in the user interface', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
								</span>
							</span>
							<input type="text" v-model="forms[currentForm]['fields'][currentField].label"/>
						</label>
					</div>
					<div class="field-setting">
						<label>
							<span class="field-title">
								<?php esc_html_e( 'Field description', 'masterstudy-lms-learning-management-system-pro' ); ?>
								<span class="tooltip-wrap">
									<i class="fas fa-info-circle"></i>
									<span class="field-tooltip"><?php esc_html_e( 'Provide a short description of the field', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
								</span>
							</span>
							<textarea type="text" v-model="forms[currentForm]['fields'][currentField].description"></textarea>
						</label>
					</div>
					<div class="field-setting" v-if="forms[currentForm]['fields'][currentField].type !== 'checkbox' && forms[currentForm]['fields'][currentField].type !== 'radio'">
						<label>
							<span class="field-title">
								<?php esc_html_e( 'Placeholder', 'masterstudy-lms-learning-management-system-pro' ); ?>
								<span class="tooltip-wrap">
									<i class="fas fa-info-circle"></i>
									<span class="field-tooltip"><?php esc_html_e( 'Add a text to an input field telling users what they should enter in this field', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
								</span>
							</span>
							<input type="text" v-model="forms[currentForm]['fields'][currentField].placeholder"/>
						</label>
					</div>
					<div class="field-setting">
						<label>
							<span class="field-title">
								<?php esc_html_e( 'Required field', 'masterstudy-lms-learning-management-system-pro' ); ?>
								<span class="tooltip-wrap">
									<i class="fas fa-info-circle"></i>
									<span class="field-tooltip"><?php esc_html_e( 'Mark the field as required', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
								</span>
							</span>
							<input type="checkbox" v-model="forms[currentForm]['fields'][currentField].required"/>
						</label>
					</div>
					<div class="field-setting" v-if="forms[currentForm].slug === 'profile_form'">
						<label>
							<span class="field-title">
								<?php esc_html_e( 'Show in public profile', 'masterstudy-lms-learning-management-system-pro' ); ?>
								<span class="tooltip-wrap">
									<i class="fas fa-info-circle"></i>
									<span class="field-tooltip"><?php esc_html_e( 'If you check this option, this information will be displayed in instructor public profile', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
								</span>
							</span>
							<input type="checkbox" v-model="forms[currentForm]['fields'][currentField].public"/>
						</label>
					</div>
					<div class="field-setting" v-if="forms[currentForm]['fields'][currentField].type === 'file'">
						<label>
							<span class="field-title">
								<?php esc_html_e( 'Allowed file extensions separated by commas', 'masterstudy-lms-learning-management-system-pro' ); ?>
								<span class="tooltip-wrap">
									<i class="fas fa-info-circle"></i>
									<span class="field-tooltip"><?php esc_html_e( 'Default: .png,.jpg,.jpeg,.mp4,.pdf', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
								</span>
							</span>
							<input type="text" placeholder="<?php esc_attr_e( 'Leave empty to use default values (.png,.jpg,.jpeg,.mp4,.pdf)', 'masterstudy-lms-learning-management-system-pro' ); ?>" v-model="forms[currentForm]['fields'][currentField].extensions"/>
						</label>
					</div>
					<div class="field-setting" v-if="typeof forms[currentForm]['fields'][currentField]['choices'] !== 'undefined'">
						<label>
							<span class="field-title">
								<?php esc_html_e( 'Choices', 'masterstudy-lms-learning-management-system-pro' ); ?>
								<span class="tooltip-wrap">
									<i class="fas fa-info-circle"></i>
									<span class="field-tooltip"><?php esc_html_e( 'Add answer options for the dropdown field or radio button', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
								</span>
							</span>
						</label>
						<div class="choices-wrap">
							<draggable
									:list="forms[currentForm]['fields'][currentField]['choices']"
									group="{ name: 'choice', pull: 'false', put: false }">
								<div class="single-choice" v-for="(choice, index) in forms[currentForm]['fields'][currentField]['choices']">
									<i class="fas fa-arrows-alt"></i>
									<input type="text" v-model="forms[currentForm]['fields'][currentField]['choices'][index]" />
									<i class="fas fa-plus" @click="forms[currentForm]['fields'][currentField]['choices'].push('')"></i>
									<i v-if="forms[currentForm]['fields'][currentField]['choices'].length > 1" class="fas fa-minus" @click="forms[currentForm]['fields'][currentField]['choices'].splice(index, 1)"></i>
								</div>
							</draggable>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="elements">
			<div class="elements-wrap">
				<div class="sidebar-title">
					<?php esc_html_e( 'Elements', 'masterstudy-lms-learning-management-system-pro' ); ?>
				</div>
				<stm-fields-sidebar inline-template>
					<?php require_once STM_LMS_PRO_ADDONS . '/form_builder/templates/sidebar.php'; ?>
				</stm-fields-sidebar>
			</div>
		</div>
	</div>
</div>
