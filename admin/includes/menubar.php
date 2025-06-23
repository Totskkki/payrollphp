<?php
// Get the current page name for highlighting the active state
$current_page = pathinfo($_SERVER['PHP_SELF'])['filename'];
?>




<div class="sidebarMenuScroll">
  <ul class="sidebar-menu">
    <li class="<?php echo ($current_page == 'home') ? 'active current-page' : ''; ?>">
      <a href="home.php">
        <i class="bi bi-tv"></i>
        <span class="menu-text">Dashboard</span>
      </a>
    </li>
    <li class="<?php echo ($current_page == 'attendance') ? 'active current-page' : ''; ?>">
      <a href="attendance.php">
        <i class="bi bi-bar-chart"></i>
        <span class="menu-text">Attendance</span>
      </a>
    </li>
    <li class="<?php echo ($current_page == 'employee') ? 'active current-page' : ''; ?>">
      <a href="employee.php">
      <i class="bi bi-person-lines-fill"></i>
        <span class="menu-text">Employee Records</span>
      </a>
    </li>
    <li class="<?php echo ($current_page == 'unit_tracking') ? 'active current-page' : ''; ?>">
      <a href="unit_tracking.php">
        <i class="bi bi-box"></i>
        <span class="menu-text">Daily Unit Tracking</span>
      </a>
    </li>
    
    <li
      class="treeview <?php echo (in_array($current_page, ['mandatorybenefits', '13th_month', 'allowance'])) ? 'active current-page' : ''; ?>">
      <a href="#!" aria-haspopup="true" aria-expanded="false">
        <i class="bi bi-stickies"></i>
        <span class="menu-text">Benefits management</span>
      </a>
      <ul class="treeview-menu">
        <li><a href="mandatorybenefits.php"
            class="<?php echo ($current_page == 'mandatorybenefits') ? 'active current-sub' : ''; ?>">Mandatory
            benefits</a></li>
        <li><a href="13th_month.php"
            class="<?php echo ($current_page == '13th_month') ? 'active current-sub' : ''; ?>">13th Month pay
            management</a></li>
        <li><a href="allowance.php"
            class="<?php echo ($current_page == 'allowance') ? 'active current-sub' : ''; ?>">Allowance</a></li>
      </ul>
    </li>
    <li
      class="treeview <?php echo (in_array($current_page, ['department', 'schedule', 'useraccounts', 'position'])) ? 'active current-page' : ''; ?>">
      <a href="#!" aria-haspopup="true" aria-expanded="false">
        <i class="bi bi-ui-checks-grid"></i>
        <span class="menu-text">Management employee workforce</span>
      </a>
      <ul class="treeview-menu">
        <li class="<?php echo ($current_page == 'department') ? 'active current-sub' : ''; ?>">
          <a href="department.php">Department</a>
        </li>
        <li class="<?php echo ($current_page == 'position') ? 'active current-sub' : ''; ?>">
          <a href="position.php">Position</a>
        </li>
        <li>
          <a href="schedule.php" class="<?php echo ($current_page == 'schedule') ? 'active current-sub' : ''; ?>">Work
            Schedule</a>
        </li>
        <!-- <li>
          <a href="useraccounts.php"
            class="<?php echo ($current_page == 'useraccounts') ? 'active current-sub' : ''; ?>">User Accounts</a>
        </li> -->
      </ul>
    </li>


    <li
      class="treeview <?php echo (in_array($current_page, ['payroll', 'deduction', 'pay_periods', 'payroll_runs', 'bonus_incentives', 'cashadvance'])) ? 'active current-page' : ''; ?>">
      <a href="#!" aria-haspopup="true" aria-expanded="false">
        <i class="bi bi-window-sidebar"></i>
        <span class="menu-text">Payroll processing</span>
      </a>
      <ul class="treeview-menu">
       
        <li class="<?php echo ($current_page == 'deduction') ? 'active current-sub' : ''; ?>">
          <a href="deduction.php">Deduction</a>
        </li>
        <li class="<?php echo ($current_page == 'pay_periods') ? 'active current-sub' : ''; ?>">
          <a href="pay_periods.php">Pay periods</a>
        </li>
        <li class="<?php echo ($current_page == 'payroll_runs') ? 'active current-sub' : ''; ?>">
          <a href="payroll_runs.php">Payroll runs</a>
        </li>
        <li class="<?php echo ($current_page == 'payroll') ? 'active current-sub' : ''; ?>">
          <a href="payroll.php">Salary computation</a>
        </li>
        <li class="<?php echo ($current_page == 'bonus_incentives') ? 'active current-sub' : ''; ?>">
          <a href="bonus_incentives.php">Bonus incentives</a>
        </li>
        <li class="<?php echo ($current_page == 'cashadvance') ? 'active current-sub' : ''; ?>">
          <a href="cashadvance.php">Cash advance monitoring</a>
        </li>
      </ul>
    </li>

    <li class="treeview  <?php echo (in_array($current_page, ['overtimerequest', 'overtimeapproval', 'overtime_tracking', 'overtime_computation'])) ? 'active current-page' : ''; ?>">
      <a href="#!">
      <i class="bi bi-person-workspace"></i>
        <span class="menu-text">Overtime management</span>
      </a>
      <ul class="treeview-menu">
        <li class="<?php echo ($current_page == 'overtimerequest') ? 'active current-sub' : ''; ?>"><a href="overtimerequest.php"> Ovetime request</a></li>
        <!-- <li class="<?php echo ($current_page == 'overtimeapproval') ? 'active current-sub' : ''; ?>"><a href="overtimeapproval.php"> Overtime approval</a></li> -->
        <li class="<?php echo ($current_page == 'overtime_tracking') ? 'active current-sub' : ''; ?>"><a href="overtime_tracking.php">Overtime tracking</a></li>
        <li class="<?php echo ($current_page == 'overtime_computation') ? 'active current-sub' : ''; ?>"><a href="overtime_computation.php"> Overtime computation</a></li>
      </ul>
    </li>


    <li class="treeview <?php echo (in_array($current_page, ['reports_attendance','payroll_audit','reports_earnings', 'reports_other-deductions','reports_payroll', 'reports_allowance', 'reports_benefit','reports_mandec','reports_year-end','reports_overtime'])) ? 'active current-page' : ''; ?>">
      <a href="#!">
      <i class="bi bi-pencil-square"></i>
        <span class="menu-text">Reports</span>
      </a>
      <ul class="treeview-menu">
        <li style="font-size:13px;" class="<?php echo ($current_page == 'reports_attendance') ? 'active current-sub' : ''; ?>"><a href="reports_attendance.php"> Employee attendance report</a></li>
        <li style="font-size:13px;"class="<?php echo ($current_page == 'reports_payroll') ? 'active current-sub' : ''; ?>"><a href="reports_payroll.php"> Employee payroll report</a></li>
        <li style="font-size:13px;"class="<?php echo ($current_page == 'reports_allowance') ? 'active current-sub' : ''; ?>"><a href="reports_allowance.php"> Employee allowance report</a></li>
        <!-- <li style="font-size:13px;" class="<?php echo ($current_page == 'reports_benefit') ? 'active current-sub' : ''; ?>"><a href="reports_benefit.php"> Employee benefit report</a></li> -->
        <li style="font-size:13px;"class="<?php echo ($current_page == 'reports_mandec') ? 'active current-sub' : ''; ?>"><a href="reports_mandec.php">Employee Mandatory <br/>deductions report</a></li>
        <li class="<?php echo ($current_page == 'reports_overtime') ? 'active current-sub' : ''; ?>"><a href="reports_overtime.php">Overtime report</a></li>
        <li class="<?php echo ($current_page == 'reports_other-deductions') ? 'active current-sub' : ''; ?>"><a href="reports_other-deductions.php">Other Deductions report</a></li>
        <li class="<?php echo ($current_page == 'reports_earnings') ? 'active current-sub' : ''; ?>"><a href="reports_earnings.php">Payroll earnings report</a></li>
        <li class="<?php echo ($current_page == 'payroll_audit') ? 'active current-sub' : ''; ?>"><a href="payroll_audit.php">Payroll Audit</a></li>
        <li class="<?php echo ($current_page == 'reports_year-end') ? 'active current-sub' : ''; ?>"><a href="reports_year-end.php">Year end summary report</a></li>
      </ul>
    </li>



  </ul>
  </li>
  </ul>
</div>