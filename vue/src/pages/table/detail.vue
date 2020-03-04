<template>
	<Detail
		:tableCode="$route.params.tableCode"
		:name="$route.name"
		:id="$route.params.id"
		@cancel="cancel"
		@openDetail="openDetail"
		@saveElement="saveElementDetail"
		@removeElement="removeElementDetail"
		@createElement="createElementDetail"
	/>
</template>
<script>
	import Detail from '@/components/tviews/Detail.vue';
	import qs from 'qs';
	import detailFunctions from '@/mixins/detailFunctions.js';
	export default
	{
		components: {Detail},
		mixins: [detailFunctions],
		methods:
		{
			cancel()
			{
				this.$router.go(-1);
			},
			openDetail({tableCode,id})
			{
				this.$router.push({name:'tableDetail', params:{tableCode:tableCode, id:id }});
			},
			saveElementDetail(data)
			{
				let result = this.saveElement(data);
				if (result.data.success)
					this.ElMessage(this.$t('elMessages.element_saved'));
			},
			createElementDetail(data)
			{
				let result = this.createElement(data);
				if (result.data.success)
				{
					this.openDetail({tableCode:data.tableCode, id:result.data.lastid});
					this.ElMessage(this.$t('elMessages.element_created'));
				}
				else
					this.ElMessage.error(this.$t('elMessages.cant_create_element'));
			},
			removeElementDetail(data)
			{
				let result = this.removeElement(data);
				if (result.data.success)
					this.ElMessage(this.$t('elMessages.element_removed'));
			}
		}
	}
</script>