<template>
  <v-container class="fill-height" fluid>
    <v-overlay :value="loading">
      <v-progress-circular size=90 indeterminate></v-progress-circular>
    </v-overlay>
    <v-dialog
      v-model="editing"
      max-width="75%"
    >
      <v-card>
        <v-card-title v-if="editingMode == 'edit'" primary-title>查看訂單 {{editingItem.id}}</v-card-title>
        <v-card-title v-else primary-title>
          新增訂單
          <v-btn small class="ml-2" @click="triggerItemAdd" color="#007000">
            <v-icon>mdi-plus</v-icon>
            新增訂單內容
          </v-btn>
        </v-card-title>
        <v-card-text class="mb-0">
          <v-card-subtitle>訂單內容</v-card-subtitle>
          <v-data-table
            :headers="itemHeaders"
            :items="editingItem.items"
          >
            <template v-slot:item.act="{item}" >
              <!-- <v-btn icon small class="ma-2" @click="triggerItemEdit(item)" color="#00a5f0"> <v-icon>mdi-pencil</v-icon> </v-btn> -->
              <v-btn icon small class="ma-2" @click="triggerItemRemove(item)" color="#f00000"> <v-icon>mdi-trash-can-outline</v-icon> </v-btn>
            </template>
          </v-data-table>
          <div v-if="(editingMode == 'edit') && (editingItem.take_type == 'delivery'  || editingItem.take_type == '外送')">
            <v-card-subtitle>外送訂單詳細資料</v-card-subtitle>
            <v-row>
              <v-col>
                <v-text-field readonly label="買方" v-model="editingItem.order_name" />
              </v-col>
              <v-col>
                <v-text-field readonly label="買方電話" v-model="editingItem.phone_number" />
              </v-col>
              <v-col>
                <v-text-field readonly label="買方地址" v-model="editingItem.order_address" />
              </v-col>
            </v-row>
            <v-textarea
              v-model="editingItem.remarks"
              label="備註"
              class="mb-0"
              readonly
            ></v-textarea>
          </div>
          <div v-if="(editingMode == 'new')">
            <v-card-subtitle>訂單詳細資料</v-card-subtitle>
            <v-row>
              <v-col>
                <v-select 
                  label="訂單類型" 
                  v-model="editingItem.take_type" 
                  :items="[ {text: '內用', value: 'for_here'}, {text: '外帶', value: 'take_out'}, {text: '外送', value: 'delivery'} ]"
                  item-text="text"
                  item-value="value"
                />
              </v-col>
              <v-col>
                <v-select 
                  label="付款方式" 
                  v-model="editingItem.payment_method" 
                  :items="[ {text: '現金', value: 'cash'}, {text: '信用卡', value: 'credit_card'} ]"
                  item-text="text"
                  item-value="value"
                />
              </v-col>
              <v-col v-if="editingItem.take_type == 'for_here'">
                <v-text-field label="桌號" v-model="editingItem.table_number"/>
              </v-col>
            </v-row>
            <div v-if="editingItem.take_type == 'delivery'">
              <v-row>
                <v-col>
                  <v-text-field label="買方" v-model="editingItem.order_name" />
                </v-col>
                <v-col>
                  <v-text-field label="買方電話" v-model="editingItem.phone_number" />
                </v-col>
                <v-col>
                  <v-text-field label="買方地址" v-model="editingItem.order_address" />
                </v-col>
              </v-row>
              <v-textarea
                v-model="editingItem.remarks"
                label="備註"
                class="mb-0"
              ></v-textarea>
            </div>
          </div>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn v-if="editingMode != 'edit'" class="ma-2 mt-0" @click="editing = false" color="#700000">
            <v-icon>mdi-close</v-icon>
            取消
          </v-btn>
          <v-btn v-if="editingMode == 'edit'" @click="editing = false"  color="#007000">
            <v-icon>mdi-close</v-icon>
            <span>關閉</span>
          </v-btn>
          <v-btn v-else class="ma-2 mt-0" @click="add" color="#007000">
            <v-icon>mdi-check</v-icon>
            <span>送出</span>
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="itemEditing"
      max-width="75%"
    >
      <v-card>
        <v-card-title primary-title>新增菜單項目</v-card-title>
        <v-card-text>
          <v-row>
            <v-col>
              <v-autocomplete 
                label="品項"
                v-model="editingItemOfItemName"
                :items="menu"
                item-text="menu_name"
              /> 
            </v-col>
            <v-col> <v-text-field v-model="editingItemOfItem.remarks" label="備註"/> </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn class="ma-2" @click="itemEditing = false" color="#700000">
            <v-icon>mdi-close</v-icon>
            取消
          </v-btn>
          <v-btn class="ma-2" @click="addItem" color="#007000">
            <v-icon>mdi-check</v-icon>
            送出
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card
      class="mx-auto"
      elevation="3"
      min-width="90%"
      max-width="90%"
      min-height="90%"
      max-height="90%"
    >
      <v-card-title class="white--text">
        抱歉冠傑牛肉湯訂單管理系統
        <v-spacer />
        <v-btn 
          outlined
          small
          @click="$router.push('/')"
        >
          <v-icon>mdi-undo-variant</v-icon>
          返回主頁
        </v-btn>
      </v-card-title>
      <v-card-text>
        <v-card>
          <v-card-title class="white--text">
            訂單管理
            <v-btn small class="ml-2" @click="triggerAdd" color="#007000">新增</v-btn>
          </v-card-title>
          <v-card-text>
            <v-data-table
              :headers=headers
              :items=orders
            >
              <template v-slot:item.act="{ item }">
                <v-btn class="ma-2" @click="triggerEdit(item)" color="#004560">
                  <v-icon>mdi-format-list-checkbox</v-icon>
                  查看
                </v-btn>
                <v-btn class="ma-2" @click="triggerRemove(item)" color="#700000">
                  <v-icon>mdi-check</v-icon>
                  完成
                </v-btn>
              </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-card-text>  
    </v-card>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      editing: false,
      editingMode: "edit",
      itemEditing: false,
      loading: false,
      editingItemOfItem: {}, 
      editingItemOfItemName: undefined,
      editingItem: {},
      editingIdx: 0,
      editingItemOfItemIdx: 0,
      headers:[
        {text: "訂單ID", value: "order_ID", align: "center"},
        {text: "訂單類型", value: "take_type", align: "center"},
        {text: "付款方式", value: "payment_method", align: "center"},
        {text: "訂單日期", value: "date", align: "center"},
        {text: "桌號", value: "table_number", align: "center"},
        {text: "總價", value: "price_ID", align: "center"},
        {text: "操作", value: "act", align: "center"},
      ],
      orders:[],
      menu:[],
    }
  },
  computed: {
    itemHeaders(){
      if(this.editingMode == 'edit'){ 
        return [
          {text: "商品ID", value: "menu_ID", width: "12%", align: "center"},
          {text: "商品名稱", value: "menu_name", width: "12%", align: "center"},
          {text: "商品價格", value: "price", width: "12%", align: "center"},
          {text: "備註", value: "remarks", width: "44%", align: "center"},
        ];
      }
      return [
        {text: "商品ID", value: "menu_ID", width: "12%", align: "center"},
        {text: "商品名稱", value: "menu_name", width: "12%", align: "center"},
        {text: "商品價格", value: "price", width: "12%", align: "center"},
        {text: "備註", value: "remarks", width: "44%", align: "center"},
        {text: "操作", value: "act", width: "10%", align: "center"},
      ];
    }
  },
  methods: {
    triggerAdd(){
      this.editingItem = {
        id: undefined,
        order_type: undefined,
        payment_method: undefined,
        date: undefined,
        table_number: undefined,
        price: undefined,
        items:[],
        remarks: undefined,
      };
      this.editingMode = "new";
      this.editing = true;
    },
    triggerEdit(item){
      this.editingIdx = this.orders.indexOf(item);
      this.editingMode = "edit";
      Object.assign(this.editingItem, item);
      this.editing = true;
    },
    triggerItemAdd(){
      this.editingItemOfItem = {};
      this.itemEditing = true;
    },
    triggerItemRemove(item){
      console.log(item);
      console.log(this.orders[this.editingIdx]);
      this.editingItemOfItemIdx = this.editingItem.items.indexOf(item);
      console.log(this.editingItemOfItemIdx);
      this.editingItem.items.splice(this.editingItemOfItemIdx, 1);
    },
    async triggerRemove(item){
      this.loading = true;
      await this.doneOrder(item.order_ID);
      this.editingIdx = this.orders.indexOf(item);
      this.orders.splice(this.editingIdx, 1);
      this.$toast.success(`訂單已完成！`);
      this.loading = false;
    },
    addItem(){
      console.log(this.editingItemOfItemName);
      let menuItem = this.menu.find(o => o.menu_name === this.editingItemOfItemName);
      this.editingItemOfItem.menu_ID = menuItem.menu_ID;
      this.editingItemOfItem.menu_name = menuItem.menu_name;
      this.editingItemOfItem.price = menuItem.price;
      let clone = new Object();
      Object.assign(clone, this.editingItemOfItem);
      this.editingItem.items.push(clone);
      this.editingItemOfItemName = undefined;
      this.itemEditing = false;
    },
    async add(){
      this.loading = true;
      let clone = new Object();
      Object.assign(clone, this.editingItem);
      // DO AXIOS THINGS
      await this.newOrder(clone);
      // this.orders.push(clone);
      this.editing = false;
      this.loading = false;
      this.$toast.success(`已成功新增訂單！`);
      Object.assign(this.editingItem, {});
    },
    edit(){
      this.loading = true;
      this.orders[this.editingIdx].menu_name = this.editingItem.menu_name
      this.orders[this.editingIdx].price = this.editingItem.price
      // DO AXIOS THINGS
      this.editing = false;
      this.loading = false;
      this.$toast.success(`已成功編輯商品！${this.editingItem.menu_name}`);
      Object.assign(this.editingItem, {});
    },
    remove(){
      this.loading = true;
      this.orders.splice(this.editingIdx, 1);
      // DO AXIOS THINGS
      this.removing = false;
      this.loading = false;
      this.$toast.error(`已成功刪除商品！${this.editingItem.menu_name}`);
      Object.assign(this.editingItem, {});
    },
    async fetchOrder(){
      this.orders = [];
      this.loading = true;
      const res = await this.$axios.post(
        `/api/view-order.php`
      );
      console.log(res);
      res.data.forEach(async (el)=> {
        let order = el;
        order.items = await this.fetchOrderDetail(el.order_ID);
        if(order.take_type == '外送'){
          let deliveryDetails = await this.fetchDeliveryOrderDetail(el.order_ID);
          order.order_name = deliveryDetails[0].order_name;
          order.phone_number = deliveryDetails[0].phone_number;
          order.order_address = deliveryDetails[0].order_address;
          order.remarks = deliveryDetails[0].remarks;
        }
        console.log(order);
        this.orders.push(order);
      });
      this.loading = false;
    },
    async fetchOrderDetail(order_ID){
      let formdata = new FormData();
      formdata.append("order_ID", order_ID);
      const res = await this.$axios.post(
        `/api/get-detail.php`,
        formdata,
      );
      console.log(res);
      return res.data;
    },
    async fetchDeliveryOrderDetail(order_ID){
      let formdata = new FormData();
      formdata.append("order_ID", order_ID);
      const res = await this.$axios.post(
        `/api/delivery.php`,
        formdata,
      );
      console.log(res);
      return res.data;
    },
    async doneOrder(order_ID){
      let formdata = new FormData();
      formdata.append("order_ID", order_ID);
      const res = await this.$axios.post(
        `/api/delete-order.php`,
        formdata,
      );
      console.log(res);
    },
    async fetchMenu(){
      const res = await this.$axios.post(
        `/api/view-menu.php`,
        {}
      );
      this.menu = res.data;
    },
    async newOrder(item){
      console.log("---- new order ----");
      console.log(item);
      console.log("-------------------");
      // let form = new FormData();
      // for (const key in item) {
      //   const el = item[key];
      //   console.log(`${key} -> ${el}`);
      //   if(key == 'item'){
      //     form.append("item[]", el);
      //   }
      //   else{ form.append(key, el); }
      // }
      const date = new Date();
      item.date = `${date.getFullYear()}-${("0" + date.getMonth() + 1).slice(-2)}-${("0" + date.getDate()).slice(-2)}`;
      const res = await this.$axios.post(
        `/api/add-order.php`,
        item
      );
      console.log(res);
      this.fetchOrder();
    }
  },
  mounted() {
    this.fetchOrder();
    this.fetchMenu();
  },
}
// - GOTTA SAY THIS CODE IS SOOOO DIRTY, BUT I AM 2 LAZY 2 MAKE IT LEGIT
</script>
