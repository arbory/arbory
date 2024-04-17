import $ from 'jquery';
import FieldRegistry from "./Admin/FieldRegistry";
import AdminPanel from "./Admin/AdminPanel";

window.$ = $;
window.jQuery = $;


let adminPanel = new AdminPanel(FieldRegistry);

adminPanel.initialize();

export default adminPanel;

