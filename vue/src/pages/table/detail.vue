<template>
	<Detail
		:tableCode="$route.params.tableCode"
		:name="$route.name"
		:id="$route.params.id"
		@cancel="cancel"
		@openDetail="openDetail"
		@saveElement="saveElement"
		@removeElement="removeElement"
		@createElement="createElement"
	/>
</template>
<script>
	import Detail from '@/components/tviews/Detail.vue';
	import qs from 'qs';
	export default
	{
		components: {Detail},
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
			saveElement(data)
			{
				this.$store.dispatch('saveSelectedElement', data).then(()=>
				{
					this.ElMessage(this.$t('elMessages.element_saved'));
				});
			},
			async createElement(data)
			{
				let primaryKeyCode = this.$store.getters.getPrimaryKeyCode(data.tableCode);
				let setColumns  = [];
				let setValues  = [];
				for(let fieldCode in data.selectedElement)
				{
					if(primaryKeyCode == fieldCode) continue;
					setColumns.push(fieldCode);
					setValues.push(data.selectedElement[fieldCode].value);
				}

				let insertData = qs.stringify({
					insert:
					{
						table   :data.tableCode,
						columns :setColumns,
						values  :setValues
					}
				});
				let result = await this.$axios.post('/el/insert/',insertData);
				if(result.data.success == true)
				{
					this.openDetail({tableCode:data.tableCode, id:result.data.lastid});
					this.ElMessage(this.$t('elMessages.element_created'));
				}
				else
					this.ElMessage.error(this.$t('elMessages.cant_create_element'));
			},
			async removeElement(data)
			{
				let primaryKeyCode = this.$store.getters.getPrimaryKeyCode(data.tableCode);
				await this.$store.dispatch('removeRecord', {
					delete:
					{
						table: data.tableCode,
						where:
						{
							operation:'and',
							fields:[
								{
									code      : primaryKeyCode,
									operation : 'IS',
									value     : data.selectedElement[primaryKeyCode].value
								}
							]
						}
					}
				}).then(()=>
				{
					this.cancel();
					this.ElMessage(this.$t('elMessages.element_removed'));
				});
			}
		}
	}
</script>