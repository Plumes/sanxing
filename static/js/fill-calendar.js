function get_calendar(gid,year,month){
	var posting = $.post("get_calendar.php",{'gid':gid,'year':year,'month':month});
	posting.done(function(str){
		data = JSON.parse(str);
		//console.log(gid);
		//console.log(data['day_arr']);
		fill_calendar(year,month,data['day_arr']);
	});
}
function is_on_plan (year,month,day){
	var sday = new Date($("#stime").text());
	var eday = new Date($("#etime").text());
	var today = new Date();
	today.setHours(8),today.setMinutes(0),today.setSeconds(0),today.setMilliseconds(0);
	var check_day = new Date(year,month-1,day,8);
	var itime = parseInt($("#itime").text());

	//if (eday > today) eday = today;
	//console.log(check_day > eday);
	if (check_day >= today) return 0; 
	if (check_day < sday || check_day > eday) {return 0;}
	//console.log(check_day);
	var gap_day = (check_day - sday)/(1000*3600*24);
	//console.log(itime);
	if ( gap_day%(itime+1) !== 0 ) return 0;
	return 1;
}

function fill_calendar (year,month,day_arr) {
	// body...
	//var year = parseInt($("#year").text());
	//var month = parseInt($("#month").text());
	//console.log(year);
	//console.log(1 in day_arr);

	var today=new Date();
	var day = new Date(year,month-1,1);
	var first_day = day.getDay();
	var day = new Date(year,month,0);
	var last_day = day.getDate();

	//console.log(first_day);
	//处理第一周
	var day_cnt=1;
	var tbody = $("#list-calendar>tbody");
	var to_append = "<tr class=week id=week0>";
	for(var i=0;i<first_day;i=i+1){
		to_append += ("<td></td>\n");
	}
	for(var i=first_day;i<7;i=i+1,day_cnt++){
		if(day_arr.indexOf(day_cnt)>-1){
			to_append+=("<td class=good-day >"+String(day_cnt)+"</td>\n");
		}
		else if ( is_on_plan(year,month,day_cnt) ) {
			//console.log(day_cnt+1);
			to_append+=("<td class=bad-day>"+String(day_cnt)+"</td>\n");
		}
		else {
			to_append+=("<td>"+String(day_cnt)+"</td>\n");
		}
		
	}
	$(tbody).append(to_append+"</tr>");
	//处理其他周
	var week_num = Math.ceil((last_day - (7- first_day) )/ 7);
	//console.log(last_day);
	for(var j=1;j<=week_num;j++){
		to_append = ("<tr class=week id=week"+String(j)+" >");
		for (var i=0;i<7;i++){
			if(day_cnt>last_day){
				to_append += ("<td></td>\n");
			}
			else{
				if(day_arr.indexOf(day_cnt)>-1) {
					to_append+=("<td class=good-day >"+String(day_cnt++)+"</td>\n");
				}
				else if ( is_on_plan(year,month,day_cnt) ) {
					//console.log(day_cnt);
					to_append+=("<td class=bad-day>"+String(day_cnt++)+"</td>\n");
				}
				else {
					to_append+=("<td>"+String(day_cnt++)+"</td>\n");
				}
			}

		}
		$(tbody).append(to_append+"</tr>");
	}
}
