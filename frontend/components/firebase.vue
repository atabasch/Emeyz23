<template>
  <div>
    <form>

    </form>
  </div>
</template>

<script>


export default {
  data() {
    return {
      email: '',
      password: '',
      rememberMe: false,
    };
  },

  created() {


  },


  methods: {
    async submitForm() {

    },
    validateEmail(email) {
      const emailRegex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return emailRegex.test(email);
    },
  },
};
</script>
