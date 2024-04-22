new Vue({
	el:"#stm-paypal-settings",
	data:{
		pricing_plans:[],
		modes:[],
		mode_selected:'live',
		synchronization_item: null,
		synchronization_sort:0,
		synchronization_load:false,
		access_update_account:false,
	},
	created(){

		if(typeof stm_paypal_settings_data == "undefined")
			return;

		if(typeof stm_paypal_settings_data.pricing_plans != "undefined")
			this.pricing_plans = stm_paypal_settings_data.pricing_plans;

		if(typeof stm_paypal_settings_data.modes != "undefined")
			this.modes = stm_paypal_settings_data.modes;

		if(typeof stm_paypal_settings_data.mode_selected != "undefined")
			this.mode_selected = stm_paypal_settings_data.mode_selected;

		if(typeof stm_paypal_settings_data.access_update_account != "undefined")
			this.access_update_account = stm_paypal_settings_data.access_update_account;
	},
	methods:{

		sendRequest(){
			var vm = this;
			var url = currentAjaxUrl+'?action=stm_paypal_synchronization_ajax';
			var formData = new FormData;

			formData.append('plan_id', vm.pricing_plans[vm.synchronization_sort].id)
			vm.synchronization_item = vm.pricing_plans[vm.synchronization_sort].id;
			vm.pricing_plans[vm.synchronization_sort].check   = false;
			vm.pricing_plans[vm.synchronization_sort].message = null;

			this.$http.post(url, formData).then(response => {
				if(response.body.success) {
					vm.pricing_plans[vm.synchronization_sort].check = true;
					if(response.body.paypal_plan_id)
						vm.pricing_plans[vm.synchronization_sort].paypal_plan_id = response.body.paypal_plan_id;
				}else
					vm.pricing_plans[vm.synchronization_sort].message = response.body.message;

				vm.synchronization_item = 0;

				if(vm.pricing_plans.length == vm.synchronization_sort + 1) {
					vm.synchronization_load = false;
				} else {
					vm.synchronization_sort++;
					vm.sendRequest()
				}
			});
		},
		synchronization: function() {
			this.synchronization_load = true;
			this.synchronization_sort = 0;
			this.sendRequest();
		}
	}
});