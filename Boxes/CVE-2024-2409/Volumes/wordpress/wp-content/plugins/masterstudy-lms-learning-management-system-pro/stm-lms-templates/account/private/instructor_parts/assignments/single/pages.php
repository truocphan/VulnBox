<div class="asignments_grid__pagination" v-if="pages > 1">
    <ul class="page-numbers">
        <li v-for="single_page in pages" :class="single_page !== 1 && single_page !== pages && (single_page + 1 < page || single_page - 1 > page) ? 'page-points' : 'pagina'">
            <a class="page-numbers" href="#" v-if="single_page !== page" @click.prevent="page = single_page; getAssignments()">{{single_page}}</a>
            <span v-else class="page-numbers current">{{single_page}}</span>
        </li>
    </ul>
</div>