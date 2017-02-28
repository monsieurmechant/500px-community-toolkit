<template>
  <div class="followers-top">
    <div class="tabs is-centered">
      <ul class="followers-top__sort">
        <li :class="[sort === 'followers' ? 'is-active' : '']">
          <a @click="sortByFollowers()" :disabled="sort === 'followers'">
            <span class="icon is-small"><i class="fa fa-users"></i></span>
            <span>Top 50 by Followers</span>
          </a>
        </li>
        <li :class="[sort === 'affection' ? 'is-active' : '']">
          <a @click="sortByAffection()" :disabled="sort === 'followers'">
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
      <template v-for="(follower, i) in followers" v-if="!loading">
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
      <tr v-if="loading">
        <td colspan="4">
          <Loader></Loader>
        </td>
      </tr>

      </tbody>
    </table>
  </div>
</template>

<script>
    import Axios from 'axios';
    import Loader from '../UI/InlineLoader';
    import FullProfileCard from './FullProfileCard';

    export default {
        name: 'Followers-Top',
        data:  function () {
          return {
            detailsRow: 0,
            followers: [],
            loading: true,
            sort: 'followers',
          }
        },
        methods: {
          showProfileDetails(row) {
            this.detailsRow = this.detailsRow === row ? 0:row;
          },
          fetchFollowers() {
            this.loading = true;
            this.detailsRow = 0;
            Axios.get('/internal/followers', {
              params: {
                'order-by': this.sort
              }
            }).then(response => {
              this.followers = response.data.data;
              this.loading = false;
            }).catch();
          },
          sortByFollowers() {
            if (this.sort === 'followers') {
              return
            }
            this.sort = 'followers';
            this.fetchFollowers();
          },
          sortByAffection() {
            if (this.sort === 'affection') {
              return
            }
            this.sort = 'affection';
            this.fetchFollowers();
          },
        },
        components: {
          FullProfileCard,
          Loader,
        },
        mounted() {
          this.fetchFollowers();
        }
    }
</script>

<style lang="scss" scoped>
  .followers-top {
    tbody tr.follower-row {
      cursor: pointer;
    }
    .followers-top__sort {
      li.is-active a {
        cursor: default;
      }
    }
  }
</style>
