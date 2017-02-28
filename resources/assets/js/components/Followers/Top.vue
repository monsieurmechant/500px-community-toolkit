<template>
  <div class="followers-top">
    <div class="tabs is-centered">
      <ul>
        <li class="is-active">
          <a>
            <span class="icon is-small"><i class="fa fa-users"></i></span>
            <span>Top 50 by Followers</span>
          </a>
        </li>
        <li>
          <a>
            <span class="icon is-small"><i class="fa fa-heart"></i></span>
            <span>Top 50 by Affection</span>
          </a>
        </li>
      </ul>
    </div>
    <table class="table">
      <thead>
      <tr>
        <th><abbr title="Position">Pos</abbr></th>
        <th>Username</th>
        <th><abbr title="Followers">Followers</abbr></th>
        <th><abbr title="Affection">Affection</abbr></th>
      </tr>
      </thead>
      <tfoot>
      <tr>
        <th><abbr title="Position">Pos</abbr></th>
        <th>Username</th>
        <th><abbr title="Followers">Followers</abbr></th>
        <th><abbr title="Affection">Affection</abbr></th>
      </tr>
      </tfoot>
      <tbody>
      <template v-for="(follower, i) in followers">
        <tr @click="showProfileDetails(i+1)" class="follower-row">
          <td>{{ i + 1 }}</td>
          <td><a :href="`http://500px.com/${follower.username}`">{{ follower.name }}</a></td>
          <td>{{ follower.followers_count }}</td>
          <td>{{ follower.affection }}</td>
        </tr>
        <tr v-if="detailsRow === i+1">
          <td colspan="4">
            <FullProfileCard :follower-id="follower.id"></FullProfileCard>
          </td>
        </tr>
      </template>
      </tbody>
    </table>
  </div>
</template>

<script>
    import Axios from 'axios';
    import FullProfileCard from './FullProfileCard';
    export default {
        name: 'Followers-Top',
        data:  function () {
          return {
            detailsRow: 0,
            followers: [],
            loading: true,
          }
        },
        methods: {
          showProfileDetails(row) {
            this.detailsRow = this.detailsRow === row ? 0:row;
          },
        },
        components: {
          FullProfileCard,
        },
        mounted() {
          this.loading = true;
          Axios.get('/internal/followers').then(response => {
            this.followers = response.data.data;
            this.loading = false;
          }).catch();
        }
    }
</script>

<style lang="scss" scoped>
  .followers-top {
    tbody tr.follower-row {
      cursor: pointer;
    }
  }
</style>
