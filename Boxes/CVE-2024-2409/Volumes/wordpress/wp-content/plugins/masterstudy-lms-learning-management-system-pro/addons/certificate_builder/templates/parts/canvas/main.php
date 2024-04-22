<vue-draggable-resizable
		v-if="typeof certificates[currentCertificate] !== 'undefined' && typeof certificates[currentCertificate].data !== 'undefined' && typeof certificates[currentCertificate].data.fields !== 'undefined'"
		:parent="true" v-for="(field, key) in certificates[currentCertificate].data.fields"
		:w="field.w"
		:h="field.h"
		:x="field.x"
		:y="field.y"
		:lockAspectRatio="field.type === 'image' ? true : false"
		drag-cancel=".settings"
		@resizestop="onResize"
		@activated="activeField = key"
		@dragstop="onDrag">
	<div v-if="field.type === 'image'" class="image-wrap">
		<img v-if="typeof field.imageId !== 'undefined'" v-bind:src="field.content"/>

		<div v-else @click="uploadFieldImage(key)" class="uploader">
			<span>
				<?php esc_html_e( 'Select Image', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</span>
		</div>
		<i class="fa fa-trash" @click="deleteField(key)" title="<?php esc_attr_e( 'Delete field', 'masterstudy-lms-learning-management-system-pro' ); ?>"></i>
	</div>
	<div v-else v-bind:class="'field-content ' + field.classes">
		<textarea v-model="field.content"
				:readonly="field.type !== 'text'"
				v-bind:style="{
				'fontSize': field.styles.fontSize,
				'fontFamily': field.styles.fontFamily === 'OpenSans' ? 'Open Sans' : field.styles.fontFamily,
				'color': field.styles.color.hex,
				'textAlign': field.styles.textAlign,
				'textDecoration': field.styles.textDecoration ? 'underline' : 'none',
				'fontStyle': (field.styles.fontStyle && field.styles.fontStyle !== 'false') ? 'italic' : 'normal',
				'fontWeight': (field.styles.fontWeight && field.styles.fontWeight !== 'false') ? 'bold' : '400',
				}"
		></textarea>
		<div class="settings">
			<div class="font">
				<select v-model="field.styles.fontFamily">
					<option value="OpenSans">OpenSans</option>
					<option value="Montserrat">Montserrat</option>
					<option value="Merriweather">Merriweather</option>
					<option value="Katibeh">Katibeh (arab)</option>
					<option value="Amiri">Amiri (arab)</option>
					<option value="Oswald">Oswald</option>
				</select>
				<select v-model="field.styles.fontSize">
					<option value="8px">8px</option>
					<option value="10px">10px</option>
					<option value="12px">12px</option>
					<option value="14px">14px</option>
					<option value="16px">16px</option>
					<option value="18px">18px</option>
					<option value="20px">20px</option>
					<option value="24px">24px</option>
					<option value="28px">28px</option>
					<option value="32px">32px</option>
					<option value="40px">40px</option>
					<option value="60px">60px</option>
					<option value="80px">80px</option>
					<option value="100px">100px</option>
				</select>
			</div>
			<div class="font-style">
				<div class="color">
					<div class="color-value">
						<div v-bind:style="{'backgroundColor': typeof field.styles.color.hex !== 'undefined' ? field.styles.color.hex : '#000'}"></div>
					</div>
					<photoshop-picker v-model="field.styles.color"></photoshop-picker>
				</div>
				<div class="align">
					<div class="checkbox-wrap">
						<input v-bind:id="'text-align-left-' + key" type="radio" v-model="field.styles.textAlign" value="left"/>
						<label class="left" v-bind:for="'text-align-left-' + key">
							<i class="fa fa-align-left"></i>
						</label>
					</div>
					<div class="checkbox-wrap">
						<input v-bind:id="'text-align-center-' + key" type="radio" v-model="field.styles.textAlign" value="center"/>
						<label class="center" v-bind:for="'text-align-center-' + key">
							<i class="fa fa-align-center"></i>
						</label>
					</div>
					<div class="checkbox-wrap">
						<input v-bind:id="'text-align-right-' + key" type="radio" v-model="field.styles.textAlign" value="right"/>
						<label class="right" v-bind:for="'text-align-right-' + key">
							<i class="fa fa-align-right"></i>
						</label>
					</div>
				</div>
				<div class="decoration">
					<div class="checkbox-wrap">
						<input v-bind:id="'font-weight-bold-' + key" type="checkbox" v-model="field.styles.fontWeight" value="bold"/>
						<label v-bind:for="'font-weight-bold-' + key">
							<i class="fa fa-bold"></i>
						</label>
					</div>
					<div class="checkbox-wrap">
						<input v-bind:id="'font-style-italic-' + key" type="checkbox" v-model="field.styles.fontStyle" value="italic"/>
						<label v-bind:for="'font-style-italic-' + key">
							<i class="fa fa-italic"></i>
						</label>
					</div>

				</div>

			</div>
		</div>
		<i class="fa fa-trash" @click="deleteField(key)" title="<?php esc_attr_e( 'Delete field', 'masterstudy-lms-learning-management-system-pro' ); ?>"></i>
	</div>
</vue-draggable-resizable>
