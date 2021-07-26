<template>
	<Detail
		:tableCode="$route.params.tableCode"
		:name="$route.name"
		:id.sync="$route.params.id"
		:updatedElAt.sync="updatedElAt"
		@cancel="cancel"
		@openDetail="openDetail"
		@saveElement="saveElementDetail"
		@removeElement="removeElementDetail"
		@createElement="createElementDetail"
		v-if="show"
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
		data()
		{
			return {
				show:false,
				updatedElAt: new Date(),
			}
		},
		methods:
		{
			cancel()
			{
				this.$router.go(-1);
			},
			openDetail({tableCode,id})
			{
				this.$router.push({name:'tableDetail', params:{tableCode, id }});
			},
			async saveElementDetail(data)
			{
				let result = await this.saveElement(data);
				this.updatedElAt = new Date();
				if (result.data.success)
					this.ElMessage(this.$t('elMessages.element_saved'));
			},
			async createElementDetail(data)
			{
				let result = await this.createElement(data);
				if (result.data.success)
				{
					this.openDetail({tableCode:data.tableCode, id:result.data.lastid});
					this.ElMessage(this.$t('elMessages.element_created'));
				}
				else
					this.ElMessage.error(this.$t('elMessages.cant_create_element'));
			},
			async removeElementDetail(data)
			{
				let result = await this.removeElement(data);
				if (result)
					this.ElMessage(this.$t('elMessages.element_removed'));
				this.$router.go(-1);
			}
		},
		mounted()
		{
			if(this.$store.state.tables.tables.length > 0)
				this.show = true;
			this.$store.subscribe((mutation, state) => {
				if(mutation.type == 'setTables')
					this.show = true;
			});
		}
	}
</script>
