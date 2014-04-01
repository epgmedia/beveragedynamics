<script type="text/javascript">
    /***********************************************
     * Textarea Maxlength script- © Dynamic Drive (www.dynamicdrive.com)
     * This notice must stay intact for legal use.
     * Visit http://www.dynamicdrive.com/ for full source code
     ***********************************************/
    function ismaxlength(obj, mlength)
    {
        if (obj.value.length > mlength)
            obj.value = obj.value.substring(0, mlength)
    }

    function ShowDescriptions(SubDomain,val, brid) {
        myWindow = window.open(SubDomain + '/description.asp?brid=' + brid + '&id=' + val, 'Description', 'location=no,height=180,width=440,resizeable=no,scrollbars=yes,dependent=yes');
        myWindow.focus()
    }

    function moveCaret(event, objThisField, objNextField, objPrevField, nSize)
    {
        var keynum;
        if(window.event) // IE
            keynum = event.keyCode;
        else if(event.which) // Netscape/Firefox/Opera
            keynum = event.which;
        if (keynum == 37 || keynum == 39 || keynum == 38 || keynum == 40 || keynum == 8) //left, right, up, down arrows, backspace
        {
            var nCaretPosition = getCaretPosition(objThisField);
            if (keynum == 39 && nCaretPosition == nSize)
                moveToNextField(objNextField);
            if ((keynum == 37 || keynum == 8) && nCaretPosition == 0)
                moveToPrevField(objPrevField);
            return;
        }
        if (keynum == 9) //Tab
            return;
        if (objThisField.value.length >= nSize && objNextField != null)
            moveToNextField(objNextField);
    }
    function moveToNextField(objNextField)
    {
        if (objNextField == null)
            return;
        objNextField.focus();
        if (document.selection) //IE
        {
            oSel = document.selection.createRange ();
            oSel.moveStart ('character', 0);
            oSel.moveEnd ('character', objNextField.value.length);
            oSel.select();
        }
        else
        {
            objNextField.selectionStart = 0;
            objNextField.selectionEnd = objNextField.value.length;
        }
    }
    function moveToPrevField(objPrevField)
    {
        if (objPrevField == null)
            return;
        objPrevField.focus();
        if (document.selection) //IE
        {
            oSel = document.selection.createRange ();
            oSel.moveStart ('character', 0);
            oSel.moveEnd ('character', objPrevField.value.length);
            oSel.select ();
        }
        else
        {
            objPrevField.selectionStart = 0;
            objPrevField.selectionEnd = objNextField.value.length;
        }
    }
    function getCaretPosition(objField)
    {
        var nCaretPosition = 0;
        if (document.selection) //IE
        {
            var oSel = document.selection.createRange ();
            oSel.moveStart ('character', -objField.value.length);
            nCaretPosition = oSel.text.length;
        }
        if (objField.selectionStart || objField.selectionStart == '0')
            nCaretPosition = objField.selectionStart;
        return nCaretPosition;
    }
</script>

<form method="post" name="profileform" action="https://EPGMediaLLC.informz.net/clk/remote_post.asp">

    <h3>Enter Your Information</h3>
    <div>
        <label>
            <span>*</span> Required
        </label>
    </div>
    <div>
        <label for="email">Email <span>*</span>
            <input alt="Email Address" type="text" name="email" maxlength="100" value="" autofocus>
        </label>
    </div>
    <div>
        <label for="personal_5784">First Name <span>*</span>
            <input alt="First Name" type="text" name="personal_5784" value="" maxlength="500">
        </label>
    </div>
    <div>
        <label for="personal_5785">Last Name <span>*</span>
            <input alt="Last Name" type="text" name="personal_5785" value="" maxlength="500">
        </label>
    </div>
    <div>
        <label for="personal_6147">Job Title <span>*</span>
            <input alt="Title" type="text" name="personal_6147" value="" maxlength="500">
        </label>
    </div>
    <div>
        <label for="personal_5786">Company <span>*</span>
            <input alt="Company" type="text" name="personal_5786" value="" maxlength="500">
        </label>
    </div>
    <div>
        <label for="personal_6149">Address 1 <span>*</span>
            <input alt="Address 1" type="text" name="personal_6149" value="" maxlength="500">
        </label>
    </div>
    <div>
        <label for="personal_6150">Address 2
            <input alt="Address 2" type="text" name="personal_6150" value="" maxlength="500">
        </label>
    </div>
    <div>
        <label for="personal_6151">City <span>*</span>
            <input alt="City" type="text" name="personal_6151" value="" maxlength="500">
        </label>
    </div>
    <div>
        <label for="demo_810">State <span>*</span>
            <span class="dropdown">
                <select name="demo_810">
                    <option value="" selected disabled></option>
                    <option value="36647">AK</option>
                    <option value="36648">AL</option>
                    <option value="36649">AR</option>
                    <option value="36650">AZ</option>
                    <option value="36651">CA</option>
                    <option value="36652">CO</option>
                    <option value="36653">CT</option>
                    <option value="36654">DC</option>
                    <option value="36655">DE</option>
                    <option value="36656">FL</option>
                    <option value="36657">GA</option>
                    <option value="36658">HI</option>
                    <option value="36659">IA</option>
                    <option value="36660">ID</option>
                    <option value="36661">IL</option>
                    <option value="36662">IN</option>
                    <option value="36663">KS</option>
                    <option value="36664">KY</option>
                    <option value="36665">LA</option>
                    <option value="36666">MA</option>
                    <option value="36667">MD</option>
                    <option value="36668">ME</option>
                    <option value="36669">MI</option>
                    <option value="36670">MN</option>
                    <option value="36671">MO</option>
                    <option value="36672">MS</option>
                    <option value="36673">MT</option>
                    <option value="36674">NC</option>
                    <option value="36675">ND</option>
                    <option value="36676">NE</option>
                    <option value="36677">NH</option>
                    <option value="36678">NJ</option>
                    <option value="36679">NM</option>
                    <option value="36680">NV</option>
                    <option value="36681">NY</option>
                    <option value="36682">OH</option>
                    <option value="36683">OK</option>
                    <option value="36684">OR</option>
                    <option value="36685">PA</option>
                    <option value="36686">RI</option>
                    <option value="36687">SC</option>
                    <option value="36688">SD</option>
                    <option value="36689">TN</option>
                    <option value="36690">TX</option>
                    <option value="36691">UT</option>
                    <option value="36692">VA</option>
                    <option value="36693">VT</option>
                    <option value="36694">WA</option>
                    <option value="36695">WI</option>
                    <option value="36696">WV</option>
                    <option value="36697">WY</option>
                    <option value="36698">AB</option>
                    <option value="36699">BC</option>
                    <option value="36700">MB</option>
                    <option value="36701">NB</option>
                    <option value="36702">NF</option>
                    <option value="36703">NS</option>
                    <option value="36704">NT</option>
                    <option value="36705">NU</option>
                    <option value="36706">ON</option>
                    <option value="36707">PE</option>
                    <option value="36708">QC</option>
                    <option value="36709">SK</option>
                    <option value="36710">YT</option>
                    <option value="36711">AA</option>
                    <option value="36712">AE</option>
                    <option value="36713">AP</option>
                    <option value="36714">AS</option>
                    <option value="36715">FM</option>
                    <option value="36716">GU</option>
                    <option value="36717">MH</option>
                    <option value="36718">MP</option>
                    <option value="36719">PR</option>
                    <option value="36720">PW</option>
                    <option value="36721">VI</option>
                </select>
            </span>
        </label>
    </div>
    <div>
        <label for="personal_6146">Zip Code <span>*</span>
            <input alt="Zip Code" type="text" name="personal_6146" value="" size="30" maxlength="500">
        </label>
    </div>
    <div>
        <label for="demo_864">Country <span>*</span>
            <span class="dropdown">
                <select name="demo_864">
                    <option value="" selected disabled></option>
                    <option value="38373">Unknown</option>
                    <option value="38374">United States</option>
                    <option value="38375">Albania</option>
                    <option value="38376">Algeria</option>
                    <option value="38377">American Samoa</option>
                    <option value="38378">Andorra</option>
                    <option value="38379">Angola</option>
                    <option value="38380">Anguilla</option>
                    <option value="38381">Antarctica</option>
                    <option value="38382">Antigua and Barbuda</option>
                    <option value="38383">Argentina</option>
                    <option value="38384">Armenia</option>
                    <option value="38385">Aruba</option>
                    <option value="38386">Australia</option>
                    <option value="38387">Austria</option>
                    <option value="38388">Azerbaijan</option>
                    <option value="38389">Bahamas</option>
                    <option value="38390">Bahrain</option>
                    <option value="38391">Bangladesh</option>
                    <option value="38392">Barbados</option>
                    <option value="38393">Belarus</option>
                    <option value="38394">Belgium</option>
                    <option value="38395">Belize</option>
                    <option value="38396">Benin</option>
                    <option value="38397">Bermuda</option>
                    <option value="38398">Bhutan</option>
                    <option value="38399">Bolivia</option>
                    <option value="38400">Bosnia and Herzegovina</option>
                    <option value="38401">Botswana</option>
                    <option value="38402">Brazil</option>
                    <option value="38403">Brunei Darussalam</option>
                    <option value="38404">Bulgaria</option>
                    <option value="38405">Burkina Faso</option>
                    <option value="38406">Myanmar (formerly Burma)</option>
                    <option value="38407">Burundi</option>
                    <option value="38408">Cambodia</option>
                    <option value="38409">Cameroon</option>
                    <option value="38410">Canada</option>
                    <option value="38411">Cape Verde Islands</option>
                    <option value="38412">Cayman Islands</option>
                    <option value="38413">Central African Republic</option>
                    <option value="38414">Chad</option>
                    <option value="38415">Chile</option>
                    <option value="38416">China</option>
                    <option value="38417">Christmas Island</option>
                    <option value="38418">Cocos (Keeling) Islands</option>
                    <option value="38419">Colombia</option>
                    <option value="38420">Comoros</option>
                    <option value="38421">Congo</option>
                    <option value="38422">Congo, Democratic Republic of (formerly Zaire)</option>
                    <option value="38423">Cook Islands</option>
                    <option value="38424">Costa Rica</option>
                    <option value="38425">Cote d'Ivoire</option>
                    <option value="38426">Croatia</option>
                    <option value="38427">Cyprus</option>
                    <option value="38428">Czech Republic</option>
                    <option value="38429">Denmark</option>
                    <option value="38430">Djibouti</option>
                    <option value="38431">Dominica</option>
                    <option value="38432">Dominican Republic</option>
                    <option value="38433">East Timor</option>
                    <option value="38434">Ecuador</option>
                    <option value="38435">Egypt</option>
                    <option value="38436">El Salvador</option>
                    <option value="38437">England</option>
                    <option value="38438">Equatorial Guinea</option>
                    <option value="38439">Eritrea</option>
                    <option value="38440">Estonia</option>
                    <option value="38441">Ethiopia</option>
                    <option value="38442">Falkland Islands</option>
                    <option value="38443">Faroe Islands</option>
                    <option value="38444">Fiji</option>
                    <option value="38445">Finland</option>
                    <option value="38446">France</option>
                    <option value="38447">French Guiana</option>
                    <option value="38448">French Polynesia</option>
                    <option value="38449">Gabon</option>
                    <option value="38450">Gambia</option>
                    <option value="38451">Georgia</option>
                    <option value="38452">Germany</option>
                    <option value="38453">Ghana</option>
                    <option value="38454">Gibraltar</option>
                    <option value="38455">Great Britain</option>
                    <option value="38456">Greece</option>
                    <option value="38457">Greenland</option>
                    <option value="38458">Grenada</option>
                    <option value="38459">Guadeloupe</option>
                    <option value="38460">Guam</option>
                    <option value="38461">Guatemala</option>
                    <option value="38462">Guinea</option>
                    <option value="38463">Guinea-Bissau</option>
                    <option value="38464">Guyana</option>
                    <option value="38465">Haiti</option>
                    <option value="38466">Honduras</option>
                    <option value="38467">Hong Kong</option>
                    <option value="38468">Hungary</option>
                    <option value="38469">Iceland</option>
                    <option value="38470">India</option>
                    <option value="38471">Indonesia</option>
                    <option value="38472">Ireland</option>
                    <option value="38473">Israel</option>
                    <option value="38474">Italy</option>
                    <option value="38475">Jamaica</option>
                    <option value="38476">Japan</option>
                    <option value="38477">Jordan</option>
                    <option value="38478">Kazakhstan</option>
                    <option value="38479">Kenya</option>
                    <option value="38480">Kiribati</option>
                    <option value="38481">Korea, Democratic People's Republic of (North)</option>
                    <option value="38482">Korea, Republic of (South)</option>
                    <option value="38483">Kuwait</option>
                    <option value="38484">Kyrgyzstan</option>
                    <option value="38485">Lao People's Democratic Republic</option>
                    <option value="38486">Latvia</option>
                    <option value="38487">Lebanon</option>
                    <option value="38488">Lesotho</option>
                    <option value="38489">Liberia</option>
                    <option value="38490">Liechtenstein</option>
                    <option value="38491">Lithuania</option>
                    <option value="38492">Luxembourg</option>
                    <option value="38493">Macau</option>
                    <option value="38494">Macedonia</option>
                    <option value="38495">Madagascar</option>
                    <option value="38496">Malawi</option>
                    <option value="38497">Malaysia</option>
                    <option value="38498">Maldives</option>
                    <option value="38499">Mali</option>
                    <option value="38500">Malta</option>
                    <option value="38501">Marshall Islands</option>
                    <option value="38502">Martinique</option>
                    <option value="38503">Mauritania</option>
                    <option value="38504">Mauritius</option>
                    <option value="38505">Mayotte</option>
                    <option value="38506">Mexico</option>
                    <option value="38507">Micronesia, Federated States of</option>
                    <option value="38508">Moldova, Republic of</option>
                    <option value="38509">Monaco</option>
                    <option value="38510">Mongolia</option>
                    <option value="38511">Montserrat</option>
                    <option value="38512">Morocco</option>
                    <option value="38513">Mozambique</option>
                    <option value="38514">Namibia</option>
                    <option value="38515">Nauru</option>
                    <option value="38516">Nepal</option>
                    <option value="38517">Netherlands</option>
                    <option value="38518">Netherlands Antilles</option>
                    <option value="38519">New Caledonia</option>
                    <option value="38520">New Zealand</option>
                    <option value="38521">Nicaragua</option>
                    <option value="38522">Niger</option>
                    <option value="38523">Nigeria</option>
                    <option value="38524">Niue</option>
                    <option value="38525">Norfolk Island</option>
                    <option value="38526">Northern Ireland</option>
                    <option value="38527">Northern Mariana Islands</option>
                    <option value="38528">Norway</option>
                    <option value="38529">Oman</option>
                    <option value="38530">Pakistan</option>
                    <option value="38531">Palau</option>
                    <option value="38532">Panama</option>
                    <option value="38533">Papua New Guinea</option>
                    <option value="38534">Paraguay</option>
                    <option value="38535">Peru</option>
                    <option value="38536">Philippines</option>
                    <option value="38537">Pitcairn</option>
                    <option value="38538">Poland</option>
                    <option value="38539">Portugal</option>
                    <option value="38540">Puerto Rico</option>
                    <option value="38541">Qatar</option>
                    <option value="38542">Reunion</option>
                    <option value="38543">Romania</option>
                    <option value="38544">Russia</option>
                    <option value="38545">Rwanda</option>
                    <option value="38546">Saint Kitts and Nevis</option>
                    <option value="38547">Saint Lucia</option>
                    <option value="38548">Saint Vincent and the Grenadines</option>
                    <option value="38549">Samoa (Independent)</option>
                    <option value="38550">San Marino</option>
                    <option value="38551">Sao Tome and Principe</option>
                    <option value="38552">Saudi Arabia</option>
                    <option value="38553">Scotland</option>
                    <option value="38554">Senegal</option>
                    <option value="38555">Serbia and Montenegro</option>
                    <option value="38556">Seychelles</option>
                    <option value="38557">Sierra Leone</option>
                    <option value="38558">Singapore</option>
                    <option value="38559">Slovakia</option>
                    <option value="38560">Slovenia</option>
                    <option value="38561">Solomon Islands</option>
                    <option value="38562">Somalia</option>
                    <option value="38563">South Africa</option>
                    <option value="38564">Spain</option>
                    <option value="38565">Sri Lanka</option>
                    <option value="38566">Saint Helena</option>
                    <option value="38567">Saintt Pierre and Miquelon</option>
                    <option value="38568">Suriname</option>
                    <option value="38569">Swaziland</option>
                    <option value="38570">Sweden</option>
                    <option value="38571">Switzerland</option>
                    <option value="38572">Taiwan</option>
                    <option value="38573">Tajikistan</option>
                    <option value="38574">Tanzania</option>
                    <option value="38575">Thailand</option>
                    <option value="38576">Togo</option>
                    <option value="38577">Tokelau</option>
                    <option value="38578">Tonga</option>
                    <option value="38579">Trinidad and Tobago</option>
                    <option value="38580">Tunisia</option>
                    <option value="38581">Turkey</option>
                    <option value="38582">Turkmenistan</option>
                    <option value="38583">Turks and Caicos Islands</option>
                    <option value="38584">Tuvalu</option>
                    <option value="38585">Uganda</option>
                    <option value="38586">Ukraine</option>
                    <option value="38587">United Arab Emirates</option>
                    <option value="38588">United Kingdom</option>
                    <option value="38589">U.S. Minor Outlying Islands</option>
                    <option value="38590">Uruguay</option>
                    <option value="38591">Uzbekistan</option>
                    <option value="38592">Vanuatu</option>
                    <option value="38593">Vatican City State (Holy See)</option>
                    <option value="38594">Venezuela</option>
                    <option value="38595">Viet Nam</option>
                    <option value="38596">Virgin Islands (British)</option>
                    <option value="38597">Virgin Islands (U.S.)</option>
                    <option value="38598">Wales</option>
                    <option value="38599">Wallis and Futuna Islands</option>
                    <option value="38600">Western Sahara</option>
                    <option value="38601">Yemen, Arab Republic of</option>
                    <option value="38602">Zambia</option>
                    <option value="38603">Zimbabwe</option>
                    <option value="38604">Afghanistan</option>
                    <option value="38605">Australia External Territories</option>
                    <option value="38606">Ascension Island</option>
                    <option value="38607">Caribbean Nations</option>
                    <option value="38608">Carriacou</option>
                    <option value="38609">Cuba</option>
                    <option value="38610">Curacao</option>
                    <option value="38611">Western Samoa</option>
                    <option value="38612">Sudan</option>
                    <option value="38613">Syria</option>
                    <option value="38614">Iran, Islamic Republic of</option>
                    <option value="38615">Iraq</option>
                    <option value="38616">Libya</option>
                    <option value="38617">Easter Island</option>
                    <option value="38618">French Antilles</option>
                    <option value="38619">Tahiti</option>
                    <option value="38620">Diego Garcia</option>
                </select>
            </span>
        </label>
    </div>
    <h3>Demographics</h3>
    <div>
        <label for="demo_1175">
            Check the category that best describes your business
            <span class="dropdown">
                <select name="demo_1175">
                    <option value="">&nbsp;</option>
                    <option value="50333">Liquor Store (Single Unit)</option>
                    <option value="50334">Chain Liquor Store (2 or more locations)</option>
                    <option value="50335">Chain Liquor Store Headquarters</option>
                    <option value="50336">Supermarket (Single Unit)</option>
                    <option value="50337">Chain Supermarket (2 or more locations)</option>
                    <option value="50338">Chain Supermarket Headquarters</option>
                    <option value="50339">Drug Store (Single Unit)</option>
                    <option value="50340">Chain Drug Store (2 or more locations)</option>
                    <option value="50341">Chain Drug Store Headquarters</option>
                    <option value="50342">Convenience Store (Single Unit)</option>
                    <option value="50343">Chain Convenience Store (2 or more locations)</option>
                    <option value="50344">Chain Convenience Store Headquarters</option>
                    <option value="50345">Wholesaler/Distributor</option>
                    <option value="50346">Wine Store/Wine Cellar</option>
                    <option value="50347">Other (please specify):</option>
                </select>
            </span>
        </label>
    </div>
    <div>
        <label for="demo_1176">
            What is your title?
            <span class="dropdown">
                <select name="demo_1176">
                    <option value="">&nbsp;</option>
                    <option value="50348">Owner/Company Officer</option>
                    <option value="50349">District/Regional/National/Division/General/Operations/Wine/Liquor/Beer/Dept/Store Manager/Director/V.P.</option>
                    <option value="50350">Buyer/Purchasing Manager/Director/V.P.</option>
                    <option value="50351">Merchandise/Marketing Manager/Director/V.P.</option>
                    <option value="50352">Sales Manager/Director/V.P.</option>
                    <option value="50353">Chain Manager/Director/V.P.</option>
                    <option value="50354">Other (please specify): </option>
                </select>
            </span>
        </label>
    </div>
    <div>
        <label for="demo_1177">
            Are beer, wine or liquor sold at this location or at locations for which your responsible?
            <span class="dropdown">
                <select name="demo_1177">
                    <option value="">&nbsp;</option>
                    <option value="50355">Yes</option>
                    <option value="50356">No</option>
                </select>
            </span>
        </label>
    </div>
    <div>
        <label for="demo_1178">
            What type of beverages are you responsible for? (Check all that apply)<br><br>
        </label>
        <label>
            <input alt="BDX - Beer" type=checkbox name="demo_1178" value="50357"> Beer &nbsp;
        </label>
        <label>
            <input alt="BDX - Wine" type=checkbox name="demo_1178" value="50358"> Wine &nbsp;
        </label>
        <label>
            <input alt="BDX - Liquor" type=checkbox name="demo_1178" value="50359"> Liquor
        </label>
    </div>
    <div>
        <label for="demo_1179">
            What are the total beverage alcohol sales (liquor wine or beer) at this location or at locations for which you are responsible?
            <span class="dropdown">
                <select name="demo_1179">
                    <option value="">&nbsp;</option>
                    <option value="50361">Over $5 Million</option>
                    <option value="50362">$1-$5 Million</option>
                    <option value="50363">$750,001-$999,999</option>
                    <option value="50364">$500,001-$750,000</option>
                    <option value="50365">$250,001-$500,000</option>
                    <option value="50366">Less than $250,000</option>
                </select>
            </span>
        </label>
    </div>
    <div>
        <label for="demo_1180">
            What is the total sales volume at this location or at locations for which you are responsible?
            <span class="dropdown">
                <select name="demo_1180">
                    <option value="">&nbsp;</option>
                    <option value="50367">Over $12 Million</option>
                    <option value="50368">$8-$12 Million</option>
                    <option value="50369">$5 Million-$7,999,999</option>
                    <option value="50370">$3 Million-$4,999,999</option>
                    <option value="50371">$1 Million-$2,999,999</option>
                    <option value="50372">$500,000-$999,999</option>
                    <option value="50373">Less Than $500,000</option>
                </select>
            </span>
        </label>
    </div>
    <h3>Select Your Interests</h3>
    <div>
        <input alt="Beverage Dynamics Newsletters and Communications" type="checkbox" value="80077" name="interests" checked />
        <a href='#' onClick="ShowDescriptions('http://EPGMediaLLC.informz.net/EPGMediaLLC',80077,0); return false;"> Beverage Dynamics Newsletters and Communications</a>
    </div>
    <div>
        <input alt="Email communications from other beverage industry related companies" type="checkbox" value="79761" name="interests" checked /> Email communications from other beverage alcohol industry related companies
    </div>
    <p>You will receive tailored information according to your interests.</p>
    <input type=hidden name=fid value=2542>
    <input type=hidden name=b value=3678>
    <input type="hidden" name="OptoutInfo" value="">
    <input type="hidden" name="formats" value="3">
    <input type=hidden name=returnUrl value="http://www.beveragedynamics.com/enews-confirm/?zmsg=1">
    <h2>
        <input type="submit" value="Next >>" name="update">
    </h2>
</form>


<script language='javascript'>
    fullURL = document.URL
    sAlertStr = ''
    nLoc = fullURL.indexOf('&')
    if (nLoc == -1)
        nLoc = fullURL.length
    if (fullURL.indexOf('zreq=') > 0){
        sRequired = fullURL.substring(fullURL.indexOf('zreq=')+5, nLoc)
        if (sRequired.length > 0){
            sRequired = ',' + sRequired.replace('%20',' ')
            sRequired = sRequired.replace(/,/g,'\n  - ')
            sAlertStr = 'The following item(s) are required: '+sRequired + '\n'
        }
    }
    if (fullURL.indexOf('zmsg=') > 0) {
        sMessage = fullURL.substring(fullURL.indexOf('zmsg=')+5, fullURL.length)
        if (sMessage.length > 0) {
            sMessage = sMessage.replace(/%20/g, ' ')
            sMessage = sMessage.replace(/%0A/g, '\n')
            sAlertStr = sAlertStr + sMessage
        }
    }

    if (sAlertStr.length > 0)
        alert(sAlertStr)
</script>