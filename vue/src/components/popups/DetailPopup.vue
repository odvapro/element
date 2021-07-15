<template>
	<div class="detail-popup">
		<Popup
			:visible.sync="isPopupVisible"
		>
			<Detail
				:tableCode="tableCode"
				:name="name"
				:id="id"
				:element="element"
				:updatedElAt.sync="updatedElAt"
				@cancel="cancel"
				@openDetail="openDetail"
				@saveElement="saveDetailElement"
				@removeElement="removeDetailElement"
				@createElement="createDetailElement"
			/>
		</Popup>
	</div>
</template>
<script>
	import Detail from '@/components/tviews/Detail.vue'
	import detailFunctions from '@/mixins/detailFunctions.js';
	export default
	{
		props: ['tableCode', 'name', 'id', 'visible', 'element'],
		components: {Detail},
		mixins: [detailFunctions],
		data()
		{
			return {
				updatedElAt: new Date(),
			};
		},
		computed:
		{
			isPopupVisible:
			{
				get()
				{
					return this.visible
				},
				set(val)
				{
					this.$emit('update:visible', val);
				}
			}
		},
		methods:
		{
			cancel()
			{
				this.isPopupVisible = false;
			},
			openDetail(data)
			{
				this.$emit('openDetail', data);
			},
			async saveDetailElement(data)
			{
				let result = await this.saveElement(data);
				if(!result.data.success)
					return this.ElMessage(result.data.message);
				this.$emit('saveElement', ...[data, result]);
				this.updatedElAt = new Date();
			},
			async createDetailElement(data)
			{
				let result = await this.createElement(data);
				if(!result.data.success)
					return this.ElMessage(result.data.message);

				this.$emit('createElement', ...[data, result]);
			},
			async removeDetailElement(data)
			{
				let result = await this.removeElement(data);
				if(!result.data.success)
					return this.ElMessage(result.data.message);
				this.$emit('removeElement', ...[data, result]);
			},
		},
	};
</script>
<style lang="scss">
	.detail-popup
	{
		position: absolute;
		z-index: 10;
		.popup-close{display: none;}
		.popup-block {min-width: 800px; }
		.detail
		{
			padding:0;
			height:calc(100% - 144px);
			overflow: unset;
			display: flex;
			flex-direction: column;
			.detail-feilds
			{
				margin-top: auto;
				margin-bottom: auto;
			}
		}
		.detail-head{padding-right: 0px; }
	}
	@media (max-width: 768px)
	{
		.detail-popup
		{
			.popup-block { min-width: unset; }
			.detail-head__burder { display: none; }
			.detail
			{
				flex-shrink: 0;
				flex-grow: 1;
			}
		}
	}
</style>
