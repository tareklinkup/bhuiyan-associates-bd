<div id="divers">
<form @submit.prevent="saveDriver">
		<div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom: 15px;">
			<div class="col-md-5 col-md-offset-3">
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Diver Name:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="diver.name" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Contact No.:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="diver.phone" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Address:</label>
					<div class="col-md-7">
						<textarea class="form-control" v-model="diver.address"></textarea>
					</div>
				</div>

			
                <div class="form-group clearfix">
					<div class="col-md-7 text-right" style="margin-left: 7px;">
						<input type="submit" class="btn btn-success btn-sm" value="Save">
					</div>
				</div>
			</div>
		</div>
	</form>

    <div class="row">
		<div class="col-sm-12 form-inline">
			<div class="form-group">
				<label for="filter" class="sr-only">Filter</label>
				<input type="text" class="form-control" v-model="filter" placeholder="Filter">
			</div>
		</div>
		<div class="col-md-12">
			<div class="table-responsive">
				<datatable :columns="columns" :data="divers" :filter-by="filter">
					<template scope="{ row }">
						<tr>
							<td>{{ row.sl }}</td>
							<td>{{ row.name }}</td>
							<td>{{ row.phone }}</td>
							<td>{{ row.address }}</td>
							<td>
								<?php if ($this->session->userdata('accountType') != 'u') { ?>
									<button type="button" class="button edit" @click="editDriver(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<button type="button" class="button" @click="deleteDriver(row.id)">
										<i class="fa fa-trash"></i>
									</button>
								<?php } ?>
							</td>
						</tr>
					</template>
				</datatable>
				<datatable-pager v-model="page" type="abbreviated" :per-page="per_page"></datatable-pager>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>

<script>
    const app = new Vue({
        el: '#divers',
        data: {
            diver: {
                id: null,
                name: '',
                phone: '',
                address: ''
            },
            divers: [],
            columns: [
                {
                    label: 'Sl',
                    field: 'sl',
                    align: 'center',
                    filterable: false
                },
                {
                    label: 'Name',
                    field: 'name',
                    align: 'center'
                },
                {
                    label: 'Phone',
                    field: 'phone',
                    align: 'center'
                },
                {
                    label: 'Address',
                    field: 'address',
                    align: 'center'
                },
                {
                    label: 'Action',
                    align: 'center',
                    filterable: false
                }
            ],
            page: 1,
            per_page: 10,
            filter: ''
        },
        created() {
            this.getDrivers();
        },
        methods: {
            getDrivers() {
                axios.get('/get_drivers')
                .then(res => {
                    this.divers = res.data.map((item, sl)=> {
                        item.sl = sl + 1;
                        return item;
                    });
                })
            },
            saveDriver() {
                let url = '';
                if(this.diver.id != null) {
                    url = '/update_driver';
                } else {
                    url = '/add_driver'
                    delete this.diver.id
                }

                axios.post(url, this.diver)
                .then(res => {
                    if(res.data.success) {
                        alert(res.data.message)
                        this.resetForm();
                        this.getDrivers();
                    } else {
                        alert(res.data.message)
                    }
                })
                .catch(err => {
                    console.log(err.response.data.message)
                })
            },
            editDriver(diver) {
                Object.keys(this.diver).forEach(key => {
                    this.diver[key] = diver[key]
                })
            },
            deleteDriver(driverId) {
                if(confirm('Are you sure to delete ?')) {
                    axios.post('/delete_driver', {driverId: driverId})
                    .then(res => {
                        if(res.data.success) {
                            alert(res.data.message);
                            this.getDrivers();
                        } else {
                            alert(res.data.message)
                        }
                    })
                }
            },
            resetForm() {
                this.diver.id = null;
                this.diver.name = '';
                this.diver.phone = '';
                this.diver.address = '';
            }
        }
    })
</script>