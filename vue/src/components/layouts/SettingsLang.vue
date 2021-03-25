<template>
	<div class="settings-lang-wr">
		<div class="settings-lang">
			<div class="settings-lang__label">
				{{ $t('settingsLang.select_language') }}
			</div>
			<div class="settings-lang-list">
				<List>
					<template v-slot:selected>
						<ListOption :current=true>{{ $store.getters.langStr }}</ListOption>
					</template>
					<ListOption
						v-for="(lang, langIndex) in $store.getters.langList"
						:key="langIndex"
						@select="changeLang(langIndex)"
					>{{ lang }}</ListOption>
				</List>
			</div>
		</div>
	</div>
</template>
<script>

	export default
	{
		methods:
		{
			async changeLang(language)
			{
				let user = JSON.parse(this.$cookie.get('user'));
				user.language = language;
				this.$cookie.set('user', JSON.stringify(user), 12);

				await this.$store.dispatch('setLanguage', {language, id: this.$store.state.users.authUser.id});
				this.$store.commit('setLanguage', language);
			}
		},
		mounted()
		{
			if (!this.$store.state.users.authUser.is_admin)
				this.$store.commit('showLoader',false);
		}
	}
</script>
<style lang="scss">
	.settings-lang__label
	{
		margin-bottom: 10px;
	}
	.settings-lang-list
	{
		cursor: pointer;
		max-width: 160px;
		border: 1px solid rgba(103, 115, 135, 0.4);
		padding-left: 4px;
		padding-right: 10px;
		position: relative;
		display: flex;
		align-items: center;
		height: 30px;
		border-radius: 2px;
	}
</style>
