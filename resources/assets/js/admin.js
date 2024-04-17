import $ from 'jquery';
import FieldRegistry from "./Admin/FieldRegistry";
import AdminPanel from "./Admin/AdminPanel";

window.$ = $;
window.jQuery = $;


export default new AdminPanel(FieldRegistry);
