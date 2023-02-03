<template>
  <div
    :class="{
      currentColor: content.color === 'text',
      dark: content.color === 'dark',
      light: content.color === 'light',
      small: content.size === 'small',
      medium: content.size === 'medium',
      large: content.size === 'large',
      full: content.size === 'full',
    }"
    v-html="file"
  ></div>
</template>

<script>
export default {
  data() {
    return {
      file: null,
    };
  },
  async mounted() {
    try {
      const file = await this.$api.get('file-content', {
        file: this.content.icon[0].uuid,
      });

      this.file = file.code;
    } catch (e) {
      console.error(e);
    }
  },
};
</script>

<style>
.currentColor svg {
  fill: currentColor;
}

.dark {
  background: #fff;
}

.dark svg {
  fill: #000;
}

.light {
  background: #000;
}

.light svg {
  fill: #fff;
}

.small svg {
  width: 48px;
  height: auto;
}

.medium svg {
  width: 128px;
  height: auto;
}

.large svg {
  width: 256px;
  height: auto;
}

.full svg {
  width: 100%;
  height: auto;
}
</style>
