<template>
	<div>
		Extension code {{url}}
		<DetailPopup
			:visible.sync="showDetail"
			tableCode="block_type"
			id="2"
			@saveElement="saveDetailElement"
			@removeElement="removeDetailElement"
			@createElement="createDetailElement"
		></DetailPopup>
		<button class="el-btn" @click="showDetail = true">show detail</button>
	</div>
</template>
<script>
	import DetailPopup from '@/components/popups/DetailPopup';
	import detailFunctions from '@/mixins/detailFunctions.js';
	export default
	{
		components:{DetailPopup},
		mixins: [detailFunctions],
		props: ['url'],
		data()
		{
			return {
				showDetail:false
			}
		},
		methods:
		{
			saveDetailElement(data, result)
			{
				if (result.data.success)
				{
					this.showDetail = false;
					this.ElMessage(this.$t('elMessages.element_saved'));
				}
			},
			createDetailElement(data, result)
			{
				if (result.data.success)
				{
					this.ElMessage(this.$t('elMessages.element_created'));
					this.showDetail = false;
				}
				else
					this.ElMessage.error(this.$t('elMessages.cant_create_element'));
			},
			removeDetailElement(data, result)
			{
				if (result.data.success)
				{
					this.showDetail = false;
					this.ElMessage(this.$t('elMessages.element_removed'));
				}
			}
		}
	}
</script>
