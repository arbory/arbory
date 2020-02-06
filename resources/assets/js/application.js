import FieldRegistry from "./Admin/FieldRegistry";
import AdminPanel from "./Admin/AdminPanel";

let adminPanel = new AdminPanel(FieldRegistry);

adminPanel.initialize();

export default adminPanel;

