<template>
 <nav class="navbar bg-body-tertiary">
  <form class="container-fluid justify-content-start">
    <button class="btn btn-outline-success me-2" @click="getUsers()" type="button">импортировать пользователей</button>
    <p class="mt-3">Всего: <b>{{(this.total)}}</b>, Добавлено: <b>{{this.added}}</b>, Обновлено: <b>{{this.updated}}</b> </p>
  </form>
</nav>
<div v-show="isLoading" style="width:300px;margin:auto">
<b>Подождите данные загружаются ... </b>
</div>
</template>

<script>
    export default {
        data() {
	    return {
	        all:0,
	        added:0,
	        updated:0,
	        total:0,
	        isLoading:false,
	        }
        },
        mounted() {
        this.getTotal();
        },
        methods:{
          getUsers(){
                   this.isLoading = true;
	                axios.get(`https://randomuser.me/api/?results=5000`).then(({data})=>{
	                    console.log(data);
	                    this.updateUsers(data);
	                }).catch(({ response })=>{
	                    console.error(response)
	                })
                },
          updateUsers(data){
                     const config = {
	                    headers: {
	                        'content-type': 'application/json',
	                        'X-CSRF-TOKEN': this.csrf,
	                    }
	                }
                    let appObj = this ;
                  axios.post('/users/update', data, config)
                    .then(function (result) {
                     console.log(result);
                     appObj.added = result.data.created;
                     appObj.updated = result.data.updated;
                     appObj.total = result.data.total;
                     appObj.isLoading = false;

                    })
                    .catch(function (err) {
                    console.log(err);

                    })
          },
           getTotal(){
                    let appObj = this ;
	                axios.get(`/users/total`).then(({data})=>{
	                    console.log(data);
	                    appObj.total = data;
	                }).catch(({ response })=>{
	                    console.error(response)
	                })
                },      
        },
    }
</script>
