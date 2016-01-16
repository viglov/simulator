<?php
require_once dirname( __FILE__ ) . '/../header.php';
?>
<div class="content small"><br/>
    <h2 id="version">Version: 1.1.0 - Jan 2016</h2>
    <ul>
        <li><a href="#introduction">Introduction</a>
        <li><a href="#interlocks">Interlocks</a>
            <ul>
                <li><a href="#interlock1-4">Interlock 1-4</a></li>
                <li><a href="#interlock5">Interlock 5</a></li>
                <li><a href="#interlock6-7">Interlock 6-7</a></li>
                <li><a href="#interlock8">Interlock 8</a></li>
                <li><a href="#interlock9">Interlock 9</a></li>
                <li><a href="#interlock10">Interlock 10</a></li>
                <li><a href="#interlock11">Interlock 11</a></li>
                <li><a href="#interlock11">Interlock 12</a></li>
                <li><a href="#interlock11">Interlock 13</a></li>
            </ul>
        </li>
        <li><a href="#algorithms">Algorithms</a>
            <ul>
                <li><a href="#algorithme01q1">Algorithm E01Q1</a></li>
                <li><a href="#algorithmq51q52">Algorithm Q51Q52</a></li>
                <li><a href="#algorithme01q7">Algorithm E01Q7</a></li>
                <li><a href="#algorithme06q1">Algorithm E06Q1</a></li>
                <li><a href="#algorithme06q0">Algorithm E06Q0</a></li>
                <li><a href="#algorithme08q1">Algorithm E08Q1</a></li>
            </ul>
        </li>
        <li><a href="#other-algorithms">Other Algorithms</a>
            <ul>
                <li><a href="#handling-requests">Handling Requests</a></li>
                <li><a href="#updating-data">Updating Data</a></li>
                <li><a href="#el-status">Get Elements status</a></li>
                <li><a href="#el-reload">Reload Elements</a></li>
                <li><a href="#line-status">Setting Line Status</a></li>
            </ul>
        </li>
    </ul>

    <hr/>

    <h2 id="introduction">Introduction</h2>
    <p>This system is developed for educational purposes and not for production. Depending on your browser version some functionality may not work properly, so make sure you are using the latest version of your browser. Also other issues may occur. You can report any issues to viglovk@gmail.com. Pleace be accurate in issue reporting. Step by step explanation of your actions causing the issue would help for corectly reproducing and successful resolving the issue. Thank You!</p>
    <p><a href="#version">To top</a></p>

    <hr/>

    <h2 id="interlocks">Interlocks</h2>

    <h3 id="interlock1-4">Interlock 1-4</h3>
    <p><img class="alignleft" src="./images/doc/interlock_1_4-en.JPG"/>It prevents Disconnector connected to particular line of switching if the line is earthed. On the example the interlock check Earthing Switch E04Q15 and according it's position allow or disallow switching.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="interlock5">Interlock 5</h3>
    <p><img class="alignleft" src="./images/doc/interlock_5-en.JPG"/>It prevents of switching off Breaker E06Q0 if there is a couple Disconnectors switched on at the same time.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="interlock6-7">Interlock 6-7</h3>
    <p><img class="alignleft" src="./images/doc/interlock_6_7-en.JPG"/>Usually couple disconnectors can not be turned on at the same time. Intrlock 6-7 allows it in specific cases. If the lines which the Disconnectors are connected to are electrically connected then both Disconnectors can be switched on. Example of this is changing the source without interrupting the power supply.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="interlock8">Interlock 8</h3>
    <p><img class="alignleft" src="./images/doc/interlock_8-en.JPG"/>It prevents line BB1 of being earthed by checking current status of connected Disconnectors.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="interlock9">Interlock 9</h3>
    <p><img class="alignleft" src="./images/doc/interlock_9-en.JPG"/>It prevents line BB2 of being earthed by checking current status of connected Disconnectors.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="interlock10">Interlock 10</h3>
    <p><img class="alignleft" src="./images/doc/interlock_10-en.JPG"/>It prevents line BB3 of being earthed by checking current status of connected Disconnectors.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="interlock11">Interlock 11</h3>
    <p><img class="alignleft" src="./images/doc/interlock_11-en.JPG"/>It prevents backup line BB0 of being earthed by checking current status of connected Disconnectors.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="interlock12">Interlock 12</h3>
    <p><img class="alignleft" src="./images/doc/interlock_12-en.JPG"/>It prevents of connecting more than one line to backup line BB0.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="interlock13">Interlock 13</h3>
    <p><img class="alignleft" src="./images/doc/interlock_13-en.JPG"/>It prevents of switching Backup Disconnector if the backup line is on.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <hr/>

    <h2 id="algorithms">Algorithms</h2>

    <h3 id="algorithme01q1">Algorithm E01Q1</h3>
    <p><img class="alignleft" src="./images/doc/algorithm_e01q1-en.JPG"/>The algorithm applies to Disconnectors E01Q1, E01Q2, E03Q1, E03Q2, E05Q1, E05Q2, E06Q2, E06Q3, E09Q1, E09Q3, E11Q1, E11Q3, E12Q1, E12Q3.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="algorithmq51q52">Algorithm Q51Q52</h3>
    <p><img class="alignleft" src="./images/doc/algorithm_q51q52-en.JPG"/>The algorithm applies to Earthing Switches E01Q51, E01Q52, E03Q51, E03Q52, E05Q51, E05Q52, E08Q51, E08Q52, E09Q51, E09Q52, E11Q51, E11Q52, E12Q51, E12Q52.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="algorithme01q7">Algorithm E01Q7</h3>
    <p><img class="alignleft" src="./images/doc/algorithm_e01q7-en.JPG"/>The algorithm applies to Disconnectors E01Q7, E03Q7, E05Q7, E09Q7, E11Q7, E12Q7.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="algorithme06q1">Algorithm E06Q1</h3>
    <p><img class="alignleft" src="./images/doc/algorithm_e06q1-en.JPG"/>The algorithm applies to Disconnectors E06Q1, E06Q10.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="algorithme06q0">Algorithm E06Q0</h3>
    <p><img class="alignleft" src="./images/doc/algorithm_e06q0-en.JPG"/>The algorithm applies to Disconnector E06Q0.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="algorithme08q1">Algorithm E08Q1</h3>
    <p><img class="alignleft" src="./images/doc/algorithm_e08q1-en.JPG"/>The algorithm applies to Disconnectors E08Q1, E08Q2, E08Q3.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <hr/>

    <h2 id="other-algorithms">Other Algorithms</h2>

    <h3 id="handling-requests">Handling requests</h3>
    <p><img class="alignleft" src="./images/doc/ajax-en.jpg"/>Algorithm handling server requests.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="updating-data">Updating Data</h3>
    <p><img class="alignleft" src="./images/doc/update_obj-en.JPG"/>Receive, process and update data from a request.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="el-status">Get Elements status</h3>
    <p><img class="alignleft" src="./images/doc/get_status-en.JPG"/>Check the status of an element - ON or OFF.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="el-reload">Reload Elements</h3>
    <p><img class="alignleft" src="./images/doc/reload_objects-en.JPG"/>After processing data update element's status.</p>
    <p class="clear"><a href="#version">To top</a></p>

    <h3 id="line-status">Setting Line Status</h3>
    <p><img class="alignleft" src="./images/doc/set_line_status-en.JPG"/>Setting Line Status for correct visual presentation.</p>
    <p class="clear"><a href="#version">To top</a></p>
</div>
<?php
require_once dirname( __FILE__ ) . '/../footer.php';
