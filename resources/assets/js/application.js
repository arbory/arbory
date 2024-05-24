import jQuery from 'jquery';
import FieldRegistry from "./Admin/FieldRegistry";
import AdminPanel from "./Admin/AdminPanel";

// Use this if you want to expose it into a full window scope
Object.assign(window, { $: jQuery, jQuery });


let adminPanel = new AdminPanel(FieldRegistry);

adminPanel.initialize();

export default adminPanel;

