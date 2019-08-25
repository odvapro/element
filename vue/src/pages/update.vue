<template>
	<div class="update-wrapper">
		<div class="update-head">
			<div class="update-head-name">
				<div class="update-icon-wrapper"> ⚙️ </div>
				<div class="update-name-wrapper">
					<div class="update-head-label">Update</div>
					<div class="update-head-descr">Check and update element</div>
				</div>
			</div>
		</div>
		<div class="update-content">
			<div class="update-version">
				Current version: <em>{{ currentVersion }}</em>
				<div v-if="showLatestIsInstalled && !showLoader"> You have the latest version</div>
			</div>
			<template v-if="successUpdate">
				<div class="update-success">System has been updated successfully</div>
			</template>
			<Loader class="update__loader" v-if="showLoader" />
			<div class="update-buttons" v-if="!successUpdate">
				<button @click="checkVersion()" class="el-gbtn">Check version</button>
				<button @click="update()" v-if="canUpdate" class="el-btn">Update to {{ newVersion }}</button>
			</div>
		</div>
	</div>
</template>
<script>
	import Loader from '@/components/forms/Loader.vue';
	export default
	{
		components:{Loader},
		data()
		{
			return {
				currentVersion        : '',
				canUpdate             : false,
				newVersion            : '',
				showLatestIsInstalled : false,
				successUpdate         : false,
				showLoader            : false
			}
		},
		methods:
		{
			/**
			 * Проверка существования обновления
			 */
			async checkVersion()
			{
				this.showLoader = true;
				let result = await this.$axios.get('/settings/checkVersion/');
				this.showLoader = false;
				if(typeof result.data.success == 'undefined')
					return this.ElMessage.error('Something goes wrong!');
				if(result.data.result == true)
				{
					this.canUpdate = true;
					this.newVersion = result.data.new_version;
				}
				else
					this.showLatestIsInstalled = true;
			},

			/**
			 * Обновление системы
			 */
			async update()
			{
				this.showLoader = true;
				let result = await this.$axios.get('/settings/update/');
				this.showLoader = false;
				if(typeof result.data.success != 'undefined' && result.data.success)
					this.successUpdate = true;
			}
		},
		/**
		 * Доастаем текущую версию системы
		 */
		async mounted()
		{
			let result = await this.$axios.get('/settings/getCurrentVersion/');
			if(typeof result.data.success != 'undefined' && result.data.success)
				this.currentVersion = result.data.version;
		}
	}
</script>
<style lang="scss">
	.update-wrapper {padding: 23px 0 23px 21px; }
	.update-head
	{
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		margin-bottom: 13px;
		padding-right: 95px;
	}
	.update-head-name
	{
		display: flex;
		align-items: center;
	}
	.update-icon-wrapper
	{
		margin-right: 3px;
		font-weight: 500;
		line-height: normal;
		font-size: 20px;
		color: #000000;
	}
	.update-head-label
	{
		font-family: $rBold;
		font-size: 20px;
		color: #191C21;
		line-height: 22px;
		text-transform: capitalize;
	}
	.update-head-descr
	{
		color: rgba(103, 115, 135, 0.4);
		font-size: 10px;
		text-transform: lowercase;
	}
	.update-version
	{
		font-style: normal;
		font-weight: normal;
		font-size: 14px;
		line-height: 18px;
		color: #677387;
		margin-bottom: 13px;
		em{font-style: normal; font-weight: bold; color:#191C21;}
	}
	.update-buttons .el-gbtn {margin-right: 17px; }
	.update-success {color:#3A8406; font-size: 14px;}
	.update__loader{margin-bottom: 20px;}
</style>